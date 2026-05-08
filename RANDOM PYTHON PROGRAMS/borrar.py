import glfw
import OpenGL.GL as gl
import imgui
from imgui.integrations.glfw import GlfwRenderer

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
    window = impl_glfw_init()
    imgui.create_context()
    impl = GlfwRenderer(window)

    while not glfw.window_should_close(window):
        glfw.poll_events()
        impl.process_inputs()
        imgui.new_frame()

        # Ventana de "Hola Mundo"
        imgui.begin("Hola Mundo")
        imgui.text("Hola, este es un ejemplo simple usando ImGui con Python!")
        imgui.end()

        imgui.render()
        gl.glClearColor(0.1, 0.1, 0.1, 1)
        gl.glClear(gl.GL_COLOR_BUFFER_BIT)
        impl.render(imgui.get_draw_data())
        glfw.swap_buffers(window)

    impl.shutdown()
    glfw.terminate()

if __name__ == "__main__":
    main()