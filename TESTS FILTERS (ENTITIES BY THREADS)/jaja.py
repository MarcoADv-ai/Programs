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
                # Cambiar el valor del offset a 1 para "ocultar" la entidad si el filtro está activo
                if filter_entities and hide_address is not None:
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
def impl_glfw_init(window_name="Hola Mundo ImGui", width=800, height=600):
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

    while not glfw.window_should_close(window):
        glfw.poll_events()
        impl.process_inputs()
        imgui.new_frame()

        # Ventana de "Hola Mundo"
        imgui.begin("Control de Entidades")
        imgui.text("Hola, este es un ejemplo simple usando ImGui con Python!")
        _, filter_entities = imgui.checkbox("Filtrar Entidades", filter_entities)
        imgui.end()

        imgui.render()
        gl.glClearColor(0.1, 0.1, 0.1, 1)
        gl.glClear(gl.GL_COLOR_BUFFER_BIT)
        impl.render(imgui.get_draw_data())
        glfw.swap_buffers(window)

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
