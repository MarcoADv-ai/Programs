import time
import threading
import pyMeow as pm

# Nombre del proceso del juego
game_process_name = "infinity-ro.exe"
fast_refresh_pointer_offset = 0xD18A78  # Offset del puntero
fast_refresh_value = 0  # Valor a escribir en el puntero

# Inicializar variable de control
fast_refresh_active = False

# Mostrar ASCII Art al iniciar
ascii_art = r'''
██████  ██    ██     ███████ ███    ███  █████  ██      ██      
██   ██  ██  ██      ██      ████  ████ ██   ██ ██      ██      
██████    ████       ███████ ██ ████ ██ ███████ ██      ██      
██   ██    ██             ██ ██  ██  ██ ██   ██ ██      ██      
██████     ██        ███████ ██      ██ ██   ██ ███████ ███████

                            
'''
print(ascii_art)

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
