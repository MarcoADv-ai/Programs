import time
import pyMeow as pm
import threading

# Nombre del proceso del juego
game_process_name = "HoneyRO.exe"

# Dirección base y offsets iniciales
base_address = 0x00A43F44
job_id_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0x254]
x_coord_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0xAC]
y_coord_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0xB0]
hide_entity_offsets_base = [0xCC, 0x10, 0x4, 0x8, 0x2D4]

# Número máximo de entidades a leer
max_entities = 10

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

# Función para escribir en la memoria
def write_pointer_address(base, offsets, value):
    address = pm.r_int(pm_proc, base)
    for offset in offsets[:-1]:
        address = pm.r_int(pm_proc, address + offset)
        if address == 0:  # Verifica si la dirección leída es válida
            return None
    final_address = address + offsets[-1]
    pm.w_int(pm_proc, final_address, value)
    return final_address

# Función para generar los offsets acumulados
def generate_entity_offsets(base_offsets, entity_index):
    new_offsets = base_offsets[:]
    for _ in range(entity_index):
        new_offsets.insert(-2, 0x4)  # Insertamos 0x4 en la penúltima posición antes del último offset (que es fijo)
    return new_offsets

# Función que será ejecutada en un hilo para leer la entidad correspondiente
def read_entity_data(entity_index):
    while True:
        # Generar offsets para la entidad correspondiente
        job_id_offsets = generate_entity_offsets(job_id_offsets_base, entity_index)
        x_coord_offsets = generate_entity_offsets(x_coord_offsets_base, entity_index)
        y_coord_offsets = generate_entity_offsets(y_coord_offsets_base, entity_index)
        hide_entity_offsets = generate_entity_offsets(hide_entity_offsets_base, entity_index)

        # Leer el Job ID
        entity_base = module_base + base_address
        job_id_address = read_pointer_address(entity_base, job_id_offsets)
        
        if job_id_address is not None:  # Asegúrate de que la dirección no sea nula
            job_id = pm.r_int(pm_proc, job_id_address)
        else:
            print(f"No se pudo leer la dirección del Job ID para la entidad {entity_index}.")
            continue

        # Leer las coordenadas solo si se leyó el Job ID
        if job_id is not None:
            x_address = read_pointer_address(entity_base, x_coord_offsets)
            y_address = read_pointer_address(entity_base, y_coord_offsets)
            hide_address = read_pointer_address(entity_base, hide_entity_offsets)

            if x_address is not None and y_address is not None:
                x_coord = pm.r_int(pm_proc, x_address)
                y_coord = pm.r_int(pm_proc, y_address)
                # Cambiar el valor del offset a 1 para "ocultar" la entidad
                if hide_address is not None:
                    write_pointer_address(entity_base, hide_entity_offsets, 1)
                    print(f"Entidad {entity_index + 1} con Job ID {job_id} encontrada en las coordenadas: ({x_coord}, {y_coord}). La entidad ha sido borrada.")
                else:
                    print(f"No se pudo leer la dirección de ocultación para la entidad {entity_index}.")
            else:
                print(f"No se pudieron leer las coordenadas para la entidad {entity_index}.")
        else:
            print(f"No se encontró ninguna entidad con el Job ID para la entidad {entity_index}.")

        # Un pequeño retraso para no sobrecargar el CPU
        time.sleep(0.1)  # Puedes ajustar el tiempo de espera si lo deseas

# Crear y lanzar un hilo para cada entidad
threads = []
for entity_index in range(max_entities):
    thread = threading.Thread(target=read_entity_data, args=(entity_index,))
    threads.append(thread)
    thread.start()

# Esperar a que todos los hilos terminen (esto no sucederá porque están en un bucle infinito)
for thread in threads:
    thread.join()
