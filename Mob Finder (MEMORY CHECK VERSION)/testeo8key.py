import time
import threading
import pyMeow as pm
import keyboard
import glfw
import OpenGL.GL as gl
import imgui
from imgui.integrations.glfw import GlfwRenderer
import configparser
import os
from keyauth import api
import hashlib
import sys
from datetime import datetime, timezone
from colorama import init, Fore, Style
import ctypes

# Inicializar colorama
init()

# Animación RGB en texto
def rgb_text_effect(text, delay=0.1):
    lines = text.split("\n")
    num_lines = len(lines)

    for i in range(256):  # Ciclo para generar el efecto RGB
        r = (i % 256)
        g = (i * 2 % 256)
        b = (i * 3 % 256)

        color_code = f"\033[38;2;{r};{g};{b}m"

        sys.stdout.write(f"\033[{num_lines}F")  # Mueve el cursor hacia el principio del bloque

        for line in lines:
            sys.stdout.write(f"{color_code}{line}\033[0m\n")

        sys.stdout.flush()  # Asegura que se actualice la consola inmediatamente
        time.sleep(delay)

game_process_name = "HoneyRO.exe"
base_address = 0x00A43F44
job_id_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0x254]
max_entities = 30

# Archivo de configuración
CONFIG_FILE = "config.ini"

# Variables globales
finder_key = "1"
teleport_key = "5"
teleport_delay_ms = 450
attacking = False
stop_attack = threading.Event()
lock = threading.Lock()

# Lista de Job IDs a buscar
target_job_ids = []

# Entrada de texto para nueva Job ID
new_job_id_input = ""

# Estado del finder activado desde GUI
finder_enabled = False

# Función para obtener el checksum
def getchecksum():
    md5_hash = hashlib.md5()
    try:
        with open(sys.argv[0], "rb") as file:
            md5_hash.update(file.read())
    except Exception as e:
        print(f"Error calculando checksum: {e}")
        sys.exit(1)
    return md5_hash.hexdigest()

# Integración con KeyAuth
keyauthapp = api(
    name="test",
    ownerid="7qKDWi4hyd",
    secret="a7ee9eff0d7030f6a81811187aeabdcbf790bd9316d3468bb859f6b58c783589",
    version="1.0",
    hash_to_check=getchecksum()
)

def login():
    try:
        user = input('Provide username: ')
        password = input('Provide password: ')
        keyauthapp.login(user, password)
    except KeyboardInterrupt:
        os._exit(1)

login()


# Continuar con el código original...

# Cargar la configuración de teclas y delay
def load_config():
    global finder_key, teleport_key, teleport_delay_ms
    config = configparser.ConfigParser()
    if os.path.exists(CONFIG_FILE):
        config.read(CONFIG_FILE)
        finder_key = config.get("Keys", "finder_key", fallback="1")
        teleport_key = config.get("Keys", "teleport_key", fallback="5")
        teleport_delay_ms = config.getint("Keys", "teleport_delay_ms", fallback=450)
    else:
        save_config()

def save_config():
    config = configparser.ConfigParser()
    config["Keys"] = {
        "finder_key": finder_key,
        "teleport_key": teleport_key,
        "teleport_delay_ms": str(teleport_delay_ms)
    }
    with open(CONFIG_FILE, "w") as configfile:
        config.write(configfile)

# Proceso del juego
try:
    pm_proc = pm.open_process(game_process_name)
except Exception as e:
    print(Fore.RED + f"No se encontró el proceso '{game_process_name}'. Error: {e}")
    exit()

module_info = pm.get_module(pm_proc, game_process_name)
if module_info is None:
    print(Fore.RED + f"No se pudo obtener la información del módulo para '{game_process_name}'.")
    exit()
module_base = module_info["base"]

def read_pointer_address(base, offsets):
    address = pm.r_int(pm_proc, base)
    for offset in offsets[:-1]:
        address = pm.r_int(pm_proc, address + offset)
        if address == 0:
            return None
    return address + offsets[-1]

def generate_entity_offsets(base_offsets, entity_index):
    new_offsets = base_offsets[:]
    for _ in range(entity_index):
        new_offsets.insert(-2, 0x4)
    return new_offsets

def attack_loop():
    global attacking
    while not stop_attack.is_set():
        keyboard.press_and_release(teleport_key)
        time.sleep(teleport_delay_ms / 1000.0)
    attacking = False

def read_entities_loop():
    global attacking
    while not stop_attack.is_set():
        for entity_index in range(max_entities):
            offsets = generate_entity_offsets(job_id_offsets_base, entity_index)
            entity_base = module_base + base_address
            job_id_address = read_pointer_address(entity_base, offsets)
            if job_id_address:
                job_id = pm.r_int(pm_proc, job_id_address)
                if job_id in target_job_ids:
                    print(Fore.GREEN + f"✅ Job ID {job_id} detectado en entidad {entity_index + 1}.")
                    stop_attack.set()
                    return
        time.sleep(0.2)

def key_monitor():
    global attacking
    while True:
        if finder_enabled and keyboard.is_pressed(finder_key) and not attacking:
            with lock:
                if not attacking:
                    print(Fore.CYAN + "▶️ Finder iniciado")
                    attacking = True
                    stop_attack.clear()
                    threading.Thread(target=attack_loop, daemon=True).start()
                    threading.Thread(target=read_entities_loop, daemon=True).start()

        if keyboard.is_pressed("end") and attacking:
            with lock:
                if attacking:
                    print(Fore.RED + "⛔ Finder detenido manualmente con END")
                    stop_attack.set()
                    attacking = False

        time.sleep(0.1)

def impl_glfw_init(window_name="MobFinder", width=450, height=400):
    if not glfw.init():
        print(Fore.RED + "No se pudo inicializar GLFW")
        exit(1)
    glfw.window_hint(glfw.CONTEXT_VERSION_MAJOR, 3)
    glfw.window_hint(glfw.CONTEXT_VERSION_MINOR, 3)
    glfw.window_hint(glfw.OPENGL_PROFILE, glfw.OPENGL_CORE_PROFILE)
    glfw.window_hint(glfw.OPENGL_FORWARD_COMPAT, gl.GL_TRUE)
    window = glfw.create_window(int(width), int(height), window_name, None, None)
    if not window:
        glfw.terminate()
        print(Fore.RED + "No se pudo crear la ventana")
        exit(1)
    glfw.make_context_current(window)
    return window

def main():
    global finder_key, teleport_key, teleport_delay_ms, new_job_id_input, finder_enabled

    window = impl_glfw_init()
    imgui.create_context()
    impl = GlfwRenderer(window)

    available_keys = [f"F{i}" for i in range(1, 13)] + [chr(c) for c in range(ord("A"), ord("N"))] + [str(i) for i in range(1, 10)]
    selected_finder_index = available_keys.index(finder_key) if finder_key in available_keys else 0
    selected_teleport_index = available_keys.index(teleport_key) if teleport_key in available_keys else 0

    while not glfw.window_should_close(window):
        glfw.poll_events()
        impl.process_inputs()
        imgui.new_frame()

        imgui.begin("Estado")
        imgui.text("Presiona la tecla de Finder para iniciar.")
        imgui.text(f"Finder activo: {'Sí' if attacking else 'No'}")
        imgui.separator()
        imgui.text("Job IDs actualmente buscadas:")
        for jid in target_job_ids:
            imgui.bullet_text(str(jid))
        imgui.end()

        imgui.begin("Configuración de Teclas")

        imgui.text("Finder Key:")
        changed, selected_finder_index = imgui.combo("##finder", selected_finder_index, available_keys)
        if changed:
            finder_key = available_keys[selected_finder_index]
            save_config()

        imgui.text("Teleport Key:")
        changed, selected_teleport_index = imgui.combo("##teleport", selected_teleport_index, available_keys)
        if changed:
            teleport_key = available_keys[selected_teleport_index]
            save_config()

        imgui.text(f"Delay(ms): {teleport_delay_ms}")
        changed, teleport_delay_ms = imgui.slider_int("##delay", teleport_delay_ms, 100, 1000)
        if changed:
            teleport_delay_ms = max(100, teleport_delay_ms)
            save_config()

        imgui.separator()
        imgui.text("Activar Finder:")
        changed, finder_enabled = imgui.checkbox("##finder_enabled", finder_enabled)

        imgui.end()

        imgui.begin("ID")

        changed, new_job_id_input = imgui.input_text("ID-MOB", new_job_id_input, 32)

        if imgui.button("ADD"):
            try:
                jid = int(new_job_id_input)
                if jid not in target_job_ids:
                    target_job_ids.append(jid)
                    print(Fore.GREEN + f"🔹 Job ID {jid} añadida a la lista.")
                new_job_id_input = ""
            except ValueError:
                print(Fore.RED + "⚠️ Entrada inválida. Solo números.")

        imgui.same_line()
        if imgui.button("CLEAR"):
            target_job_ids.clear()
            print(Fore.GREEN + "🧹 Lista de Job IDs limpiada.")

        imgui.end()

        imgui.render()
        gl.glClearColor(0.1, 0.1, 0.1, 1)
        gl.glClear(gl.GL_COLOR_BUFFER_BIT)
        impl.render(imgui.get_draw_data())
        glfw.swap_buffers(window)

        time.sleep(1 / 20)  # Limita la GUI a 20 FPS

    impl.shutdown()
    glfw.terminate()

if __name__ == "__main__":
    load_config()
    threading.Thread(target=key_monitor, daemon=True).start()

    # Animación de bienvenida
    text = """
    ██████  ██    ██     ███████ ███    ███  █████  ██      ██
    ██   ██  ██  ██      ██      ████  ████ ██   ██ ██      ██
    ██████    ████       ███████ ██ ████ ██ ███████ ██      ██
    ██   ██    ██             ██ ██  ██  ██ ██   ██ ██      ██
    ██████     ██        ███████ ██      ██ ██   ██ ███████ ███████
    """
    rgb_text_effect(text, delay=0.01)

    # Espera 5 segundos y luego oculta la consola
    time.sleep(5)
    ctypes.windll.user32.ShowWindow(ctypes.windll.kernel32.GetConsoleWindow(), 0)

    # Arranca la GUI
    main()
