from keyauth import api
import time
import threading
import pyMeow as pm
from colorama import init, Fore, Style
from datetime import datetime, timezone
import hashlib
import sys
import os
from time import sleep

# Inicializar colorama
init()


def rgb_text_effect(text, delay=0.1):
    """
    Muestra un texto con un efecto RGB que reemplaza completamente el bloque anterior sin parpadeos.
    :param text: Texto que se imprimirá.
    :param delay: Tiempo entre actualizaciones del color (en segundos).
    """
    # Divide el texto en líneas para manejar los movimientos del cursor
    lines = text.split("\n")
    num_lines = len(lines)

    for i in range(256):  # Ciclo para generar el efecto RGB
        # Generar colores dinámicos
        r = (i % 256)
        g = (i * 2 % 256)
        b = (i * 3 % 256)


        color_code = f"\033[38;2;{r};{g};{b}m"


        sys.stdout.write(f"\033[{num_lines}F")  # Mueve el cursor hacia el principio del bloque

        for line in lines:
            sys.stdout.write(f"{color_code}{line}\033[0m\n")

        sys.stdout.flush()  # Asegura que se actualice la consola inmediatamente
        time.sleep(delay)

def getchecksum():
    md5_hash = hashlib.md5()
    try:
        with open(sys.argv[0], "rb") as file:
            md5_hash.update(file.read())
    except Exception as e:
        print(Fore.RED + f"Error calculando checksum: {e}" + Style.RESET_ALL)
        sys.exit(1)
    return md5_hash.hexdigest()

keyauthapp = api(
    name="test",
    ownerid="7qKDWi4hyd",
    secret="a7ee9eff0d7030f6a81811187aeabdcbf790bd9316d3468bb859f6b58c783589",
    version="1.0",
    hash_to_check=getchecksum()
)

def answer():
    try:
        user = input('Provide username: ')
        password = input('Provide password: ')
        keyauthapp.login(user, password)
    except KeyboardInterrupt:
        os._exit(1)

answer()

print(Fore.CYAN + "\nUser data: " + Style.RESET_ALL)
print(Fore.CYAN + "Username: " + keyauthapp.user_data.username + Style.RESET_ALL)
sleep(2)
print(Fore.YELLOW + "Your IP address: " + keyauthapp.user_data.ip + Style.RESET_ALL)
sleep(2)
print(Fore.YELLOW + "Take responsibility for your actions if you attempt to crack or share the program with people who did not purchase it" + Style.RESET_ALL)
sleep(2)
print(Fore.GREEN + "Created at: " + datetime.fromtimestamp(int(keyauthapp.user_data.createdate), timezone.utc).strftime('%Y-%m-%d %H:%M:%S') + Style.RESET_ALL)
print(Fore.RED + "Key Expires at: " + datetime.fromtimestamp(int(keyauthapp.user_data.expires), timezone.utc).strftime('%Y-%m-%d %H:%M:%S') + Style.RESET_ALL)
sleep(2)
print(Fore.GREEN + "Loading..." + Style.RESET_ALL)
sleep(2)
os.system('cls')
sleep(1)

# Nombre del proceso del juego
game_process_name = "infinity-ro.exe"
fast_refresh_pointer_offset = 0xD18A78  # Offset del puntero
fast_refresh_value = 0  # Valor a escribir en el puntero

# Inicializar variable de control
fast_refresh_active = False

text = """
██████  ██    ██     ███████ ███    ███  █████  ██      ██
██   ██  ██  ██      ██      ████  ████ ██   ██ ██      ██
██████    ████       ███████ ██ ████ ██ ███████ ██      ██
██   ██    ██             ██ ██  ██  ██ ██   ██ ██      ██
██████     ██        ███████ ██      ██ ██   ██ ███████ ███████
"""

rgb_text_effect(text, delay=0.01)

# Mostrar ASCII Art al iniciar
'''print(Fore.GREEN + """
██████  ██    ██     ███████ ███    ███  █████  ██      ██
██   ██  ██  ██      ██      ████  ████ ██   ██ ██      ██
██████    ████       ███████ ██ ████ ██ ███████ ██      ██
██   ██    ██             ██ ██  ██  ██ ██   ██ ██      ██
██████     ██        ███████ ██      ██ ██   ██ ███████ ███████
""" + Style.RESET_ALL)'''
                            

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
            except Exception as e:
                print(f"Error al escribir en el puntero: {e}")



# Función principal para manejar la lógica por consola
def main():
    global fast_refresh_active

    print("=== Configuración de Fast Refresh ===")
    print("Comandos disponibles:")
    print("  1: Activar Fast Refresh")
    print("  2: Desactivar Fast Refresh")
    print("  3: Salir")

    while True:
        comando = input("Ingresa un comando: ").strip()

        if comando == "1":
            fast_refresh_active = True
            print("Fast Refresh activado.")
        elif comando == "2":
            fast_refresh_active = False
            print("Fast Refresh desactivado.")
        elif comando == "3":
            print("Saliendo del programa.")
            break
        else:
            print("Comando no reconocido. Por favor, intenta de nuevo.")

# Inicia el hilo para el spam de Fast Refresh
threading.Thread(target=fast_refresh_spam, daemon=True).start()

# Ejecuta la lógica por consola
if __name__ == "__main__":
    main()
