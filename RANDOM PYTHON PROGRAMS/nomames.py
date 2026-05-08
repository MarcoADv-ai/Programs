import time
import threading
import pyMeow as pm
import glfw
import OpenGL.GL as gl
import imgui
from imgui.integrations.glfw import GlfwRenderer

# Nombre del proceso del juego
game_process_name = "xroclient.bin"
fast_refresh_pointer_offset = 0xB9A4F4  # Offset del puntero
fast_refresh_value = 1215752191  # Valor a escribir en el puntero

# Inicializar variable de control
fast_refresh_active = False

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

# Dirección del puntero
fast_refresh_pointer = module_base + fast_refresh_pointer_offset

# Función que escribe continuamente en la dirección del puntero
def fast_refresh_spam():
    global fast_refresh_active
    while True:
        if fast_refresh_active:
            try:
                pm.w_int(pm_proc, fast_refresh_pointer, fast_refresh_value)
                print(f"Escribiendo valor {fast_refresh_value} en {hex(fast_refresh_pointer)}")
            except Exception as e:
                print(f"Error al escribir en el puntero: {e}")
        else:
            print("Fast Refresh está desactivado.")
        time.sleep(0.1)  # Intervalo entre escrituras

# Inicializa GLFW para crear una ventana
def impl_glfw_init(window_name="Fast Refresh GUI", width=450, height=100):
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

# Configuración principal de la GUI
def main():
    global fast_refresh_active

    window = impl_glfw_init()
    imgui.create_context()
    impl = GlfwRenderer(window)

    while not glfw.window_should_close(window):
        glfw.poll_events()
        impl.process_inputs()
        imgui.new_frame()

        # Ventana principal
        imgui.begin("Configuración de Fast Refresh")
        _, fast_refresh_active = imgui.checkbox("Fast Refresh", fast_refresh_active)
        imgui.text(f"Estado: {'Activo' if fast_refresh_active else 'Inactivo'}")
        imgui.end()

        imgui.render()
        gl.glClearColor(0.1, 0.1, 0.1, 1)
        gl.glClear(gl.GL_COLOR_BUFFER_BIT)
        impl.render(imgui.get_draw_data())
        glfw.swap_buffers(window)

    impl.shutdown()
    glfw.terminate()

# Inicia el hilo para el spam de Fast Refresh
threading.Thread(target=fast_refresh_spam, daemon=True).start()

# Inicia la GUI
if __name__ == "__main__":
    main()
