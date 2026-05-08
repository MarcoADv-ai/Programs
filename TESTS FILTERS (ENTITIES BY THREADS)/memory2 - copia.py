import time
import pyMeow as pm
import numpy as np
import cv2
import win32gui
import win32con

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

# Función para dibujar en la ventana del juego
def draw_line_on_game_window(x_start, y_start, x_end, y_end):
    hwnd = win32gui.FindWindow(None, game_process_name)  # Encuentra la ventana del juego
    if hwnd:
        # Captura la imagen de la ventana
        left, top, right, bottom = win32gui.GetWindowRect(hwnd)
        width = right - left
        height = bottom - top
        hwnd_dc = win32gui.GetWindowDC(hwnd)
        mem_dc = win32gui.CreateCompatibleDC(hwnd_dc)
        hbitmap = win32gui.CreateCompatibleBitmap(hwnd_dc, width, height)
        win32gui.SelectObject(mem_dc, hbitmap)
        
        # Crea una imagen en blanco para dibujar
        img = np.zeros((height, width, 3), dtype=np.uint8)

        # Dibuja la línea
        cv2.line(img, (x_start, y_start), (x_end, y_end), (0, 0, 255), 2)  # Línea roja

        # Copia la imagen en el contexto de la ventana
        win32gui.BitBlt(hwnd_dc, 0, 0, width, height, mem_dc, 0, 0, win32con.SRCCOPY)
        
        # Limpia los recursos
        win32gui.DeleteObject(hbitmap)
        win32gui.DeleteDC(mem_dc)
        win32gui.ReleaseDC(hwnd, hwnd_dc)

# Bucle principal
while True:
    # Leer el Job ID
    entity_base = module_base + base_address
    job_id_address = read_pointer_address(entity_base, job_id_offsets)
    
    if job_id_address is not None:  # Asegúrate de que la dirección no sea nula
        job_id = pm.r_int(pm_proc, job_id_address)
    else:
        print("No se pudo leer la dirección del Job ID.")
        time.sleep(0.1)
        continue

    # Si el Job ID es el que buscamos, leemos las coordenadas
    if job_id == target_job_id:
        # Leer las coordenadas X e Y
        x_address = read_pointer_address(entity_base, x_coord_offsets)
        y_address = read_pointer_address(entity_base, y_coord_offsets)

        if x_address is not None and y_address is not None:
            x_coord = pm.r_int(pm_proc, x_address)
            y_coord = pm.r_int(pm_proc, y_address)

            print(f"Entidad con Job ID {target_job_id} encontrada en las coordenadas: ({x_coord}, {y_coord})")

            # Dibujar línea en la ventana del juego
            # Ajusta las coordenadas de inicio (puedes poner el punto desde donde quieras dibujar la línea)
            draw_line_on_game_window(400, 300, x_coord, y_coord)
        else:
            print("No se pudieron leer las direcciones de las coordenadas.")
    else:
        print(f"No se encontró ninguna entidad con el Job ID {target_job_id}.")

    # Un pequeño retraso para no sobrecargar el CPU
    time.sleep(0.1)  # Puedes ajustar el tiempo de espera si lo deseas
