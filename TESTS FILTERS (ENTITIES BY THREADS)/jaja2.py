import time
import pyMeow as pm
import threading
import glfw
import OpenGL.GL as gl
import imgui
from imgui.integrations.glfw import GlfwRenderer

# Nombre del proceso del juego
game_process_name = "HoneyRO.exe"

# Dirección base y offsets iniciales
base_address = 0x00A43F44
job_id_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0x254]
x_coord_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0xAC]
y_coord_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0xB0]
hide_entity_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0x2D4]

# IDs específicas a filtrar
filter_ids = [
    1474, 1438, 1640, 1641, 1642, 1643, 1644, 1645, 1631, 1101, 1749, 1011,
    2228, 2229, 2230, 2231, 2232, 2233, 2234, 2279, 1757, 1758, 1759, 1760,
    1192, 1060, 1054, 2280, 1515, 2206, 2154, 1508, 1509, 1179, 1194, 1156,
    1180, 2357, 2359, 2361, 1189, 1029, 1127, 2277, 2144, 2145, 1617, 1620,
    1621, 1765, 1365, 1791, 1431, 1872, 2082, 1605, 1756, 1427, 1419, 1566,
    1603, 1531, 1564, 1868, 1606, 2278, 1786, 1787, 1608, 1886, 1834, 1835,
    1560, 1739, 1740, 1607, 1788, 1691, 2157, 1490, 1477, 1604, 1471, 2027,
    1439, 1473, 1522, 1458, 1464, 1558, 1624, 1793, 1709, 1710, 1711, 1712,
    1364, 1594, 1600, 1601, 1602, 1891, 1922, 1923, 1924, 1925, 1659, 1660,
    1661, 1662, 1663, 1750
]

# Número máximo de entidades a leer
max_entities = 10
filter_entities = False

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

# Función para escribir en la memoria
def write_pointer_address(base, offsets, value):
    address = pm.r_int(pm_proc, base)
    for offset in offsets[:-1]:
        address = pm.r_int(pm_proc, address + offset)
        if address == 0:  # Verifica si la dirección leída es válida
            return None
    final_address = address + offsets[-1]
    pm.w_int(pm_proc, final_address, value)
    return final_address

# Función para generar los offsets acumulados
def generate_entity_offsets(base_offsets, entity_index):
    new_offsets = base_offsets[:]
    for _ in range(entity_index):
        new_offsets.insert(-2, 0x4)  # Insertamos 0x4 en la penúltima posición antes del último offset (que es fijo)
    return new_offsets

# Función que será ejecutada en un hilo para leer la entidad correspondiente
def read_entity_data(entity_index):
    while True:
        # Generar offsets para la entidad correspondiente
        job_id_offsets = generate_entity_offsets(job_id_offsets_base, entity_index)
        x_coord_offsets = generate_entity_offsets(x_coord_offsets_base, entity_index)
        y_coord_offsets = generate_entity_offsets(y_coord_offsets_base, entity_index)
        hide_entity_offsets = generate_entity_offsets(hide_entity_offsets_base, entity_index)

        # Leer el Job ID
        entity_base = module_base + base_address
        job_id_address = read_pointer_address(entity_base, job_id_offsets)
        
        if job_id_address is not None:  # Asegúrate de que la dirección no sea nula
            job_id = pm.r_int(pm_proc, job_id_address)
        else:
            print(f"No se pudo leer la dirección del Job ID para la entidad {entity_index}.")
            continue

        # Leer las coordenadas solo si se leyó el Job ID
        if job_id is not None:
            x_address = read_pointer_address(entity_base, x_coord_offsets)
            y_address = read_pointer_address(entity_base, y_coord_offsets)
            hide_address = read_pointer_address(entity_base, hide_entity_offsets)

            if x_address is not None and y_address is not None:
                x_coord = pm.r_int(pm_proc, x_address)
                y_coord = pm.r_int(pm_proc, y_address)
                # Cambiar el valor del offset a 1 para "ocultar" la entidad si el filtro está activo y el Job ID coincide
                if filter_entities and job_id in filter_ids and hide_address is not None:
                    write_pointer_address(entity_base, hide_entity_offsets, 1)
                    print(f"Entidad {entity_index + 1} con Job ID {job_id} encontrada en las coordenadas: ({x_coord}, {y_coord}). La entidad ha sido borrada.")
                else:
                    print(f"Entidad {entity_index + 1} con Job ID {job_id} encontrada en las coordenadas: ({x_coord}, {y_coord})")
            else:
                print(f"No se pudieron leer las coordenadas para la entidad {entity_index}.")
        else:
            print(f"No se encontró ninguna entidad con el Job ID para la entidad {entity_index}.")

        # Un pequeño retraso para no sobrecargar el CPU
        time.sleep(0.1)  # Puedes ajustar el tiempo de espera si lo deseas

# Crear y lanzar un hilo para cada entidad
threads = []
for entity_index in range(max_entities):
    thread = threading.Thread(target=read_entity_data, args=(entity_index,))
    threads.append(thread)
    thread.start()

# Inicializa GLFW para crear una ventana
def impl_glfw_init(window_name="Filtro Slaves Test", width=450, height=100):
    if not glfw.init():
        print("No se pudo inicializar GLFW")
        exit(1)
    glfw.window_hint(glfw.CONTEXT_VERSION_MAJOR, 3)
    glfw.window_hint(glfw.CONTEXT_VERSION_MINOR, 3)
    glfw.window_hint(glfw.OPENGL_PROFILE, glfw.OPENGL_CORE_PROFILE)
    glfw.window_hint(glfw.OPENGL_FORWARD_COMPAT, gl.GL_TRUE)
    window = glfw.create_window(int(width), int(height), window_name, None, None)
    if not window:
        glfw.terminate()
        print("No se pudo crear la ventana")
        exit(1)
    glfw.make_context_current(window)
    return window

# Configuración principal
def main():
    global filter_entities
    window = impl_glfw_init()
    imgui.create_context()
    impl = GlfwRenderer(window)

        # Cambiar colores de la GUI
    style = imgui.get_style()
    style.colors[imgui.COLOR_WINDOW_BACKGROUND] = (0.0, 0.0, 0.0, 0.0)  # Color de fondo de la ventana
    style.colors[imgui.COLOR_TEXT] = (1.0, 1.0, 1.0, 1.0)  # Color del texto
    style.colors[imgui.COLOR_BUTTON] = (0.5, 0.5, 0.5, 1.0)  # Color de los botones
    style.colors[imgui.COLOR_BUTTON_HOVERED] = (0.7, 0.7, 0.7, 1.0)  # Color de los botones al pasar el ratón
    style.colors[imgui.COLOR_BUTTON_ACTIVE] = (0.9, 0.9, 0.9, 1.0)  # Color de los botones al hacer clic


    # Limitar la tasa de FPS
    target_fps = 20
    frame_time = 1.0 / target_fps  # Tiempo por cuadro en segundos

    while not glfw.window_should_close(window):
        start_time = time.time()  # Captura el tiempo al inicio del ciclo

        glfw.poll_events()
        impl.process_inputs()
        imgui.new_frame()

        # Ventana de "Hola Mundo"
        imgui.begin("Entidades test")
        imgui.text("xd")
        _, filter_entities = imgui.checkbox("Filtrar Slaves", filter_entities)
        imgui.end()

        imgui.render()
        gl.glClearColor(0.1, 0.1, 0.1, 1)
        gl.glClear(gl.GL_COLOR_BUFFER_BIT)
        impl.render(imgui.get_draw_data())
        glfw.swap_buffers(window)

        # Control de FPS
        elapsed_time = time.time() - start_time
        if elapsed_time < frame_time:
            time.sleep(frame_time - elapsed_time)

    impl.shutdown()
    glfw.terminate()



# Lanzar hilos de lectura de entidades en segundo plano
def entity_reader():
    for entity_index in range(max_entities):
        thread = threading.Thread(target=read_entity_data, args=(entity_index,))
        thread.start()

if __name__ == "__main__":
    threading.Thread(target=entity_reader).start()
    main()
