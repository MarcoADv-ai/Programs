import ctypes
from time import sleep
import os

# Definición de estructuras necesarias para el driver
class NF_MOUSE_REQUEST(ctypes.Structure):
    _fields_ = [
        ("x", ctypes.c_int),
        ("y", ctypes.c_int),
        ("ButtonFlags", ctypes.c_short)
    ]

class DriverComms:
    def __init__(self):
        self.kernel32 = ctypes.WinDLL('kernel32', use_last_error=True)
        self.hDriver = None
        
        # Configurar CreateFileA
        self.CreateFileA = self.kernel32.CreateFileA
        self.CreateFileA.argtypes = [
            ctypes.c_char_p,
            ctypes.c_uint32,
            ctypes.c_uint32,
            ctypes.c_void_p,
            ctypes.c_uint32,
            ctypes.c_uint32,
            ctypes.c_void_p
        ]
        self.CreateFileA.restype = ctypes.c_void_p
        
        # Configurar DeviceIoControl
        self.DeviceIoControl = self.kernel32.DeviceIoControl
        self.DeviceIoControl.argtypes = [
            ctypes.c_void_p,
            ctypes.c_uint32,
            ctypes.c_void_p,
            ctypes.c_uint32,
            ctypes.c_void_p,
            ctypes.c_uint32,
            ctypes.POINTER(ctypes.c_uint32),
            ctypes.c_void_p
        ]
        self.DeviceIoControl.restype = ctypes.c_bool
        
        # Configurar CloseHandle
        self.CloseHandle = self.kernel32.CloseHandle
        self.CloseHandle.argtypes = [ctypes.c_void_p]
        self.CloseHandle.restype = ctypes.c_bool
        
    def connect(self):
        try:
            # Verificar que el archivo bs.sys existe
            current_dir = os.path.dirname(os.path.abspath(__file__))
            driver_path = os.path.join(current_dir, "bs.sys")
            print(f"Buscando driver en: {driver_path}")
            
            if not os.path.exists(driver_path):
                print(f"Error: No se encontró el archivo {driver_path}")
                return False

            # Intentar diferentes nombres de dispositivo
            device_names = [
                b"\\\\.\\cla300",
                b"\\\\.\\bs",
                b"\\Device\\cla300",
                b"\\Device\\bs"
            ]
            
            for device_name in device_names:
                print(f"Intentando conectar con: {device_name.decode()}")
                self.hDriver = self.CreateFileA(
                    device_name,
                    0xC0000000,    # Acceso genérico lectura/escritura
                    3,             # Modo de compartición
                    None,          # Sin atributos de seguridad
                    3,             # OPEN_EXISTING
                    0,             # Sin atributos de archivo
                    None          # Sin template
                )
                
                if self.hDriver != ctypes.c_void_p(-1).value:
                    print(f"Conexión exitosa con: {device_name.decode()}")
                    return True
                    
                error = ctypes.get_last_error()
                print(f"Error al intentar {device_name.decode()}: {error}")
            
            print("No se pudo conectar con ningún nombre de dispositivo")
            return False
            
        except Exception as e:
            print(f"Error al cargar el driver: {str(e)}")
            return False
    
    def mouse_event(self, x, y, flags=0):
        if not self.hDriver or self.hDriver == ctypes.c_void_p(-1).value:
            return False
            
        request = NF_MOUSE_REQUEST()
        request.x = int(x)
        request.y = int(y)
        request.ButtonFlags = flags
        
        return self.DeviceIoControl(
            self.hDriver,
            0x23FACC00,  # IOCTL_MOUSE_EVENT
            ctypes.byref(request),
            ctypes.sizeof(request),
            None,
            0,
            None,
            None
        )
    
    def close(self):
        if self.hDriver and self.hDriver != ctypes.c_void_p(-1).value:
            self.CloseHandle(self.hDriver)

def test_simple_movement(driver):
    """Prueba simple de movimiento"""
    print("Moviendo el mouse 10 píxeles a la derecha...")
    driver.mouse_event(10, 0)
    sleep(1)
    print("Moviendo el mouse 10 píxeles abajo...")
    driver.mouse_event(0, 10)

def main():
    # Crear instancia del driver
    driver = DriverComms()
    
    # Intentar conectar con el driver
    if not driver.connect():
        print("Error: No se pudo conectar al driver")
        return
    
    print("Driver conectado exitosamente")
    
    try:
        # Esperar un momento antes de comenzar
        print("El movimiento comenzará en 3 segundos...")
        sleep(3)
        
        # Hacer un movimiento simple de prueba
        test_simple_movement(driver)
            
    except KeyboardInterrupt:
        print("\nPrograma interrumpido por el usuario")
    finally:
        # Cerrar el driver
        driver.close()
        print("Driver cerrado")

if __name__ == "__main__":
    main() 