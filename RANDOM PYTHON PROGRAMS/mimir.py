import ctypes
import time
import keyboard

# Definir constantes de Windows para los eventos del mouse
MOUSEEVENTF_LEFTDOWN = 0x0002
MOUSEEVENTF_LEFTUP = 0x0004

# Cargar la librería user32
user32 = ctypes.windll.user32

def click_izquierdo():
    # Simula la bajada del botón izquierdo del mouse
    ctypes.windll.user32.mouse_event(MOUSEEVENTF_LEFTDOWN, 0, 0, 0, 0)
    time.sleep(0.01)  # Espera un poco para que el clic sea "visible"
    # Simula la liberación del botón izquierdo del mouse
    ctypes.windll.user32.mouse_event(MOUSEEVENTF_LEFTUP, 0, 0, 0, 0)

def presionar_tecla_1():
    # Simula la pulsación de la tecla '1'
    user32.keybd_event(0x31, 0, 0, 0)  # Presiona la tecla '1'
    time.sleep(0.01)  # Espera un poco
    user32.keybd_event(0x31, 0, 0x0002, 0)  # Suelta la tecla '1'

print("Mantén presionada la tecla '1' para apretar '1' y hacer clic izquierdo en bucle.")

while True:
    # Si la tecla '1' está presionada
    if keyboard.is_pressed('1'):
        # Simula la pulsación de la tecla '1'
        presionar_tecla_1()
        # Simula el clic izquierdo
        click_izquierdo()
        # Puedes ajustar este valor para controlar la velocidad del spam
        time.sleep(0.1)  # Espera 100 ms entre cada acción
    else:
        # Si no se mantiene presionada, duerme un poco para evitar uso innecesario de CPU
        time.sleep(0.05)
