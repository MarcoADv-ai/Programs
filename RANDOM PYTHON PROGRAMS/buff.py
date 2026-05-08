import tkinter as tk
import pygetwindow as gw
import keyboard
import threading
import time
import ctypes
from ctypes import wintypes
import psutil
import win32api
import win32process
import win32gui
import win32con
import struct

# Variables para almacenar los ID y PIDs de las ventanas seleccionadas
window_ids = {'Priest': None, 'Soul Linker': None, 'Paladin': None}
window_pids = {'Priest': None, 'Soul Linker': None, 'Paladin': None}
healer_coordinates = {'Priest': None}

PROCESS_ALL_ACCESS = (0x000F0000 | 0x00100000 | 0xFFF)

# Función para capturar la ventana activa y su PID
def set_window(window_key):
    print("Presiona la barra espaciadora para seleccionar la ventana activa...")
    keyboard.wait('space')
    active_window = gw.getActiveWindow()
    if active_window:
        pid = get_window_pid(active_window._hWnd)
        window_ids[window_key] = active_window
        window_pids[window_key] = pid
        label_texts[window_key].config(text=f"WinID: {active_window.title} (PID: {pid})")
        print(f"Ventana seleccionada para {window_key}: {active_window.title} (PID: {pid})")
    else:
        print("No se encontró ventana activa")

# Función para obtener el PID de una ventana usando la API de Windows
def get_window_pid(hwnd):
    _GetWindowThreadProcessId = ctypes.windll.user32.GetWindowThreadProcessId
    _GetWindowThreadProcessId.argtypes = [wintypes.HWND, ctypes.POINTER(wintypes.DWORD)]
    _GetWindowThreadProcessId.restype = wintypes.DWORD

    pid = wintypes.DWORD()
    _GetWindowThreadProcessId(hwnd, ctypes.byref(pid))
    return pid.value

# Función para abrir el proceso y obtener el handle usando el PID
def open_process(pid):
    try:
        handle = ctypes.windll.kernel32.OpenProcess(PROCESS_ALL_ACCESS, False, pid)
        if not handle:
            raise ctypes.WinError()
        return handle
    except Exception as e:
        print(f"Error al abrir el proceso con PID {pid}: {e}")
        return None

# Función para leer memoria desde el proceso
def read_memory(handle, address, size):
    data = ctypes.create_string_buffer(size)
    bytes_read = ctypes.c_size_t()
    if ctypes.windll.kernel32.ReadProcessMemory(handle, ctypes.c_void_p(address), data, size, ctypes.byref(bytes_read)):
        return struct.unpack('i', data.raw)[0]  # Leer como entero (4 bytes)
    else:
        print(f"Error al leer la memoria en la dirección {hex(address)}")
        return None

# Funciones para leer los punteros de Cheat Engine en el loop de Priest
def read_skill_pointers():
    pid = window_pids['Priest']
    if pid:
        handle = open_process(pid)
        if handle:
            try:
                base_address = 0x00A43F44  # Dirección base de los punteros

                # Offsets de los punteros
                offset_skill_type = 0x3F8
                offset_skill_range = 0x400
                offset_skill_id = 0x3FC
                offset_skill_lvl = [0x404, 0x13C]

                # Leer valores de los punteros
                skill_type = read_memory(handle, base_address + offset_skill_type, 4)
                skill_range = read_memory(handle, base_address + offset_skill_range, 4)
                skill_id = read_memory(handle, base_address + offset_skill_id, 4)

                # Para el puntero de Skill Level con múltiples offsets
                pointer_address = read_memory(handle, base_address + offset_skill_lvl[0], 4)
                skill_level = read_memory(handle, pointer_address + offset_skill_lvl[1], 4) if pointer_address else None

                # Imprimir los resultados en la consola
                print(f"Skill Type: {skill_type}")
                print(f"Skill Range: {skill_range}")
                print(f"Skill ID: {skill_id}")
                print(f"Skill Level: {skill_level}")
            except Exception as e:
                print(f"Error al leer los punteros de habilidad: {e}")
            finally:
                ctypes.windll.kernel32.CloseHandle(handle)
        else:
            print("No se pudo abrir el proceso correctamente para Priest.")
    else:
        print("No se ha seleccionado ninguna ventana para Priest.")

# Función para iniciar el loop en la ventana de Priest y leer los punteros de habilidades
def start_priest_loop():
    def priest_loop_function():
        while True:
            read_skill_pointers()
            time.sleep(1)  # Ajusta el tiempo de espera entre ciclos

    # Crear un hilo para que el loop funcione sin bloquear la GUI
    threading.Thread(target=priest_loop_function, daemon=True).start()

# Configuración de la ventana principal
root = tk.Tk()
root.title("Ventana Configuración")
root.geometry("400x500")

# Diccionario para almacenar las etiquetas de WinID y PID
label_texts = {}

# Función de ayuda para crear cada sección de ventana
def create_section(frame, title, key):
    group_box = tk.LabelFrame(frame, text=title, padx=10, pady=10)
    group_box.pack(fill="both", expand="yes", padx=10, pady=10)

    label_texts[key] = tk.Label(group_box, text="WinID: None (PID: None)")
    label_texts[key].pack(anchor="w")

    set_button = tk.Button(group_box, text="Set", command=lambda: set_window(key))
    set_button.pack(anchor="e")

    if key == "Priest":
        healer_button = tk.Button(group_box, text="Coordenadas Healer", command=capture_healer_coordinates)
        healer_button.pack(anchor="w")

        friend_button = tk.Button(group_box, text="Coordenadas Friend", command=capture_friend_coordinates)
        friend_button.pack(anchor="w")

# Crear secciones para cada configuración
create_section(root, "Priest Config", "Priest")
create_section(root, "Soul Linker Config", "Soul Linker")
create_section(root, "Paladin Config", "Paladin")

# Bind para iniciar el loop en Priest con F12
keyboard.add_hotkey('f12', start_priest_loop)

root.mainloop()
