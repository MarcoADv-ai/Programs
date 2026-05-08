import time
import pyMeow as pm
import threading

# Nombre del proceso del juego
game_process_name = "HoneyRO.exe"

# Dirección base y offsets para leer el Job ID y coordenadas
base_address = 0x00A43F44
job_id_offsets = [0xCC, 0x10, 0x4, 0x8, 0x254]
x_coord_offsets = [0xCC, 0x10, 0x4, 0x8, 0xAC]
y_coord_offsets = [0xCC, 0x10, 0x4, 0x8, 0xB0]

# Abrir el proceso del juego
try:
    pm_proc = pm.open_process(game_process_name)
    print(f"Proceso '{game_process_name}' encontrado.")
except Exception as e:
    print(f"No se encontró el proceso '{game_process_name}'. Error: {e}")
    exit()

# Obtener la base del módulo para sumar con el offset
module_info = pm.get_module(pm_proc, game_process_name)
if module_info is None:
    print(f"No se pudo obtener la información del módulo para '{game_process_name}'.")
    exit()

module_base = module_info["base"]

# Función para leer la memoria con múltiples offsets
def read_pointer_address(base, offsets):
    address = pm.r_int(pm_proc, base)
    for offset in offsets[:-1]:
        address = pm.r_int(pm_proc, address + offset)
        if address == 0:  # Verifica si la dirección leída es válida
            return None
    final_address = address + offsets[-1]
    return final_address

# Función para procesar una entidad
def process_entity(entity_base):
    job_id_address = read_pointer_address(entity_base, job_id_offsets)

    if job_id_address is not None:  # Asegúrate de que la dirección no sea nula
        job_id = pm.r_int(pm_proc, job_id_address)
        
        # Leer las coordenadas X e Y
        x_address = read_pointer_address(entity_base, x_coord_offsets)
        y_address = read_pointer_address(entity_base, y_coord_offsets)

        if x_address is not None and y_address is not None:
            x_coord = pm.r_int(pm_proc, x_address)
            y_coord = pm.r_int(pm_proc, y_address)

            job_id += 0x4  # Incrementar Job ID en 0x4
            print(f"Entidad encontrada en las coordenadas: ({x_coord}, {y_coord}), Job ID: {job_id}")

            # Mover el cursor a las coordenadas de la entidad
            mouse_x_address = module_base + 0xA2EC74
            mouse_y_address = module_base + 0xA2EC78
            
            pm.w_int(pm_proc, mouse_x_address, x_coord)
            pm.w_int(pm_proc, mouse_y_address, y_coord)

            print(f"Cursor movido a: ({x_coord}, {y_coord})")
        else:
            print("No se pudieron leer las direcciones de las coordenadas.")
    else:
        print("No se pudo leer la dirección del Job ID.")

# Bucle principal
while True:
    # Leer la base de las entidades
    entity_base = module_base + base_address
    entities_found = 0

    # Simulación de detección de hasta 10 entidades
    for i in range(10):
        current_entity_base = entity_base + (i * 0x100)  # Ajusta el desplazamiento según sea necesario
        thread = threading.Thread(target=process_entity, args=(current_entity_base,))
        thread.start()
        entities_found += 1

    print(f"Se procesaron {entities_found} entidades.")
    
    # Un pequeño retraso para no sobrecargar el CPU
    time.sleep(0.1)  # Puedes ajustar el tiempo de espera si lo deseas
