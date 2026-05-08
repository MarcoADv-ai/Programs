<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Establecer zona horaria de Santiago, Chile
date_default_timezone_set('America/Santiago');

// URL de Ravenmoor
$ravenmoor_url = "https://www.ravenmoor-ro.com/?module=ranking&action=mvp";

// Archivo para almacenar MVPs conocidos
$storage_file = 'mvps_data.json';

// Archivo para almacenar historial completo de MVPs detectados
$history_file = 'mvps_history.json';

// Función para obtener datos de Ravenmoor
function fetchRavenmoorData($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

// Función para parsear MVPs desde HTML
function parseMVPs($html) {
    $mvps = [];
    
    // Usar DOMDocument para parsear HTML
    $dom = new DOMDocument();
    @$dom->loadHTML($html);
    
    $xpath = new DOMXPath($dom);
    $rows = $xpath->query("//table//tr");
    
    $first = true;
    foreach ($rows as $row) {
        if ($first) {
            $first = false;
            continue; // Skip header (primera fila es el encabezado)
        }
        
        $cols = $xpath->query(".//td", $row);
        if ($cols->length >= 4) {
            $character = trim($cols->item(0)->textContent);
            $mvp = trim($cols->item(1)->textContent);
            $experience = trim($cols->item(2)->textContent);
            $map = trim($cols->item(3)->textContent);
            
            $mvps[] = [
                'character' => $character,
                'mvp' => $mvp,
                'experience' => $experience,
                'map' => $map
            ];
        }
    }
    
    return $mvps;
}

// Crear ID único para MVP
function createMVPId($mvp) {
    return $mvp['character'] . '|' . $mvp['mvp'] . '|' . $mvp['experience'] . '|' . $mvp['map'];
}

// Cargar datos almacenados
function loadStoredData($file) {
    if (file_exists($file)) {
        $json = file_get_contents($file);
        return json_decode($json, true) ?: [];
    }
    return [];
}

// Guardar datos
function saveStoredData($file, $data) {
    file_put_contents($file, json_encode($data));
}

// Cargar historial de MVPs
function loadHistory($file) {
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $data = json_decode($json, true);
        return is_array($data) ? $data : [];
    }
    return [];
}

// Guardar historial de MVPs
function saveHistory($file, $history) {
    file_put_contents($file, json_encode($history, JSON_PRETTY_PRINT));
}

// Obtener acción
$action = isset($_GET['action']) ? $_GET['action'] : 'mvps';

if ($action == 'reset') {
    // Reiniciar tracking
    file_put_contents($storage_file, json_encode([]));
    file_put_contents($history_file, json_encode([]));
    echo json_encode(['message' => 'Tracking reset successfully', 'total_tracked' => 0]);
    exit;
}

if ($action == 'all') {
    // Obtener todos los MVPs actuales
    $html = fetchRavenmoorData($ravenmoor_url);
    $mvps = parseMVPs($html);
    echo json_encode(['mvps' => $mvps, 'count' => count($mvps)]);
    exit;
}

if ($action == 'history') {
    // Obtener el historial completo guardado
    $history = loadHistory($history_file);
    echo json_encode([
        'history' => $history,
        'total' => count($history)
    ]);
    exit;
}

// Acción principal: detectar nuevos MVPs
$html = fetchRavenmoorData($ravenmoor_url);
$current_mvps = parseMVPs($html);

// Cargar MVPs conocidos y historial
$known_mvps = loadStoredData($storage_file);
$history = loadHistory($history_file);

$new_mvps = [];
$current_time = date('Y-m-d H:i:s');

foreach ($current_mvps as $mvp) {
    $mvp_id = createMVPId($mvp);
    
    if (!in_array($mvp_id, $known_mvps)) {
        $mvp['detected_at'] = $current_time;
        $new_mvps[] = $mvp;
        $known_mvps[] = $mvp_id;
        
        // Agregar al historial
        $history[] = $mvp;
    }
}

// Guardar datos actualizados
saveStoredData($storage_file, $known_mvps);
saveHistory($history_file, $history);

// Responder
echo json_encode([
    'new_mvps' => $new_mvps,
    'total_tracked' => count($known_mvps),
    'total_history' => count($history)
]);
?>

