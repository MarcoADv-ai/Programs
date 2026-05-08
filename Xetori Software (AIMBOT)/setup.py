from keyauth import api
import ctypes
import sys
import os
import hashlib
from time import sleep
from datetime import datetime, timezone
from main import Main
import tempfile
from colorama import init, Fore, Style

# Inicializar colorama
init()

def verificar_conexion_driver():
    # Función para verificar la conexión al driver
    hDriver = ctypes.windll.kernel32.CreateFileA(b"\\\\.\\Oykyo",
                                           ctypes.c_uint32(0x80000000 | 0x40000000),
                                           ctypes.c_uint32(0x00000001 | 0x00000002),
                                           None,
                                           ctypes.c_uint32(3),
                                           ctypes.c_uint32(0),
                                           None)
    if hDriver == -1:
        print("\033[91m" + "¡Error! The connection to the xet0ri could not be opened" + "\033[0m")
        return False
    else:
        print("\033[92m" + "Successful connection" + "\033[0m")
        sleep(3)
        ctypes.windll.kernel32.CloseHandle(hDriver)
        return True

def getchecksum():
    md5_hash = hashlib.md5()
    file = open(''.join(sys.argv), "rb")
    md5_hash.update(file.read())
    digest = md5_hash.hexdigest()
    return digest

keyauthapp = api(
    name = "kngdrv",
    ownerid = "rbO8QRakro",
    secret = "f7aeaaca763d9871936f26e5db4e964ef2e627ff8a36f66ac6a674ba193e718c",
    version = "1.0",
    hash_to_check = getchecksum()
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
print(Fore.YELLOW + "Your IP address: " + keyauthapp.user_data.ip + Style.RESET_ALL)
print(Fore.YELLOW + "Take responsibility for your actions if you attempt to crack or share the program with people who did not purchase it" + Style.RESET_ALL)
sleep(3)
print(Fore.GREEN+ "Created at: " + datetime.fromtimestamp(int(keyauthapp.user_data.createdate), timezone.utc).strftime('%Y-%m-%d %H:%M:%S') + Style.RESET_ALL)
print(Fore.RED + "Key Expires at: " + datetime.fromtimestamp(int(keyauthapp.user_data.expires), timezone.utc).strftime('%Y-%m-%d %H:%M:%S') + Style.RESET_ALL)
print(Fore.GREEN + "Loading..." + Style.RESET_ALL)
sleep(5)

# Verificar si el driver esta cargadito
driver_loaded = verificar_conexion_driver()

# Ejecutar el comando solo si el driver no esta
if not driver_loaded:
    # Crear un directorio temporal en la RAM
    temp_dir = tempfile.mkdtemp()

    # Descargar los archivos de KeyAuth
    drv = "279135"
    intel_driver_id = "488165"

    # Descargar y guardar el drv en el directorio temporal
    drv_bytes = keyauthapp.file(drv)
    with open(os.path.join(temp_dir, "king.sys"), "wb") as f:
        f.write(drv_bytes)

    # Descargar y guardar el m4p en el directorio temporal
    m4p_driver_bytes = keyauthapp.file(intel_driver_id)
    with open(os.path.join(temp_dir, "M4p.exe"), "wb") as f:
        f.write(m4p_driver_bytes)

    # Ejecutar el comando desde la RAM
    os.system(f'cd {temp_dir} && M4p.exe king.sys')

    # Eliminar los archivos del directorio temporal
    os.remove(os.path.join(temp_dir, "king.sys"))
    os.remove(os.path.join(temp_dir, "M4p.exe"))
    os.rmdir(temp_dir)

# Ejecutar Main().run() independientemente de si el driver esta cargado o no
Main().run()
