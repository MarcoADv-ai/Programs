import time
import pyMeow as pm
import numpy as np
import win32gui
import win32con
import win32api

# Nombre del proceso del juego
game_process_name = "HoneyRO.exe"

# Dirección base y offsets para leer el Job ID y coordenadas
base_address = 0x00A43F44
job_id_offsets = [0xCC, 0x10, 0x4, 0x8, 0x254]
x_coord_offsets = [0xCC, 0x10, 0x4, 0x8, 0xAC]
y_coord_offsets = [0xCC, 0x10, 0x4, 0x8, 0xB0]

# Job ID objetivo que queremos encontrar
target_job_id = 1750  # 10181

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

# Registrar la clase de ventana para el overlay
class_name = "OverlayClass"

wc = win32gui.WNDCLASS()
wc.lpfnWndProc = lambda hWnd, msg, wParam, lParam: 0  # Función de procedimiento de ventana
wc.lpszClassName = class_name
wc.hInstance = win32api.GetModuleHandle(None)
win32gui.RegisterClass(wc)

# Crear una ventana overlay
overlay_hwnd = win32gui.CreateWindowEx(
    win32con.WS_EX_TOPMOST | win32con.WS_EX_LAYERED | win32con.WS_EX_TOOLWINDOW,
    class_name, "Overlay",
    win32con.WS_POPUP,
    0, 0, 800, 600,  # Ajusta el tamaño de la ventana
    None, None, wc.hInstance, None
)

win32gui.SetLayeredWindowAttributes(overlay_hwnd, win32api.RGB(0, 0, 0), 255, win32con.LWA_COLORKEY)
win32gui.ShowWindow(overlay_hwnd, win32con.SW_SHOW)

# Bucle para mantener la ventana overlay abierta
while True:
    msg = win32gui.GetMessage(0, 0, 0)
    
    if msg:  # Asegúrate de que el mensaje no sea None
        win32gui.TranslateMessage(msg)
        win32gui.DispatchMessage(msg)

    # Verifica si el overlay se creó correctamente
    if overlay_hwnd:
        print("Ventana overlay creada con éxito.")
    else:
        print("Error al crear la ventana overlay.")

    time.sleep(1)  # Ajustar el tiempo de espera si es necesario
