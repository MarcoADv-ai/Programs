import threading
import pyMeow  # Asegúrate de que pyMeow esté instalado correctamente
import time
import win32process
import win32api

# Función para obtener el PID del proceso por su nombre
def get_pid_by_name(process_name):
    process_ids = win32process.EnumProcesses()
    for pid in process_ids:
        try:
            handle = win32api.OpenProcess(0x0410, False, pid)  # 0x0410 = permisos de acceso
            exe_name = win32process.GetModuleFileNameEx(handle, 0)
            if process_name.lower() in exe_name.lower():
                return pid
        except Exception as e:
            continue
    return None

# Identificación del proceso
process_name = "HoneyRO.exe"
pid = get_pid_by_name(process_name)

if pid:
    print(f"Proceso encontrado: PID {pid}")
    handle = pyMeow.open_process(pid)
else:
    print(f"No se pudo encontrar el proceso {process_name}.")
    exit(1)

# Direcciones y offsets
base_address = 0x00A43F44  # Dirección base (modificar según tu juego)
entity_offset = 0x4  # Offset entre entidades
coordinate_x_offset = 0x50  # Offset de coordenada X
coordinate_y_offset = 0x54  # Offset de coordenada Y
job_id_offset = 0x58  # Offset del Job ID
max_entities = 10  # Cantidad máxima de entidades a buscar

# Función que se ejecutará en un hilo para obtener coordenadas y Job ID de una entidad
def process_entity(entity_address):
    # Leer las coordenadas usando pyMeow.rpm
    entity_x = pyMeow.rpm(handle, entity_address + coordinate_x_offset, pyMeow.UINT)
    entity_y = pyMeow.rpm(handle, entity_address + coordinate_y_offset, pyMeow.UINT)

    # Leer el Job ID
    job_id = pyMeow.rpm(handle, entity_address + job_id_offset, pyMeow.UINT)

    # Imprimir las coordenadas y el Job ID en la consola
    print(f"Entidad en {hex(entity_address)}: Coordenadas (X: {entity_x}, Y: {entity_y}), Job ID: {job_id}")

# Función principal para buscar todas las entidades
def search_entities():
    entity_address = base_address

    for i in range(max_entities):
        thread = threading.Thread(target=process_entity, args=(entity_address,))
        thread.start()
        entity_address += entity_offset
        time.sleep(0.01)

# Ejecutar la búsqueda de entidades
if __name__ == "__main__":
    search_entities()
