import time
import pyMeow as pm
import pygame

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

# Inicializar Pygame
pygame.init()
screen = pygame.display.set_mode((800, 600))  # Ajusta el tamaño de la ventana según sea necesario
pygame.display.set_caption("Dibujo de Línea")
running = True

# Función para leer la memoria con múltiples offsets
def read_pointer_address(base, offsets):
    address = pm.r_int(pm_proc, base)
    for offset in offsets[:-1]:
        address = pm.r_int(pm_proc, address + offset)
        if address == 0:  # Verifica si la dirección leída es válida
            return None
    final_address = address + offsets[-1]
    return final_address

# Bucle principal
while running:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            running = False

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

            # Mover el cursor a las coordenadas de la entidad
            mouse_x_address = module_base + 0xA2EC74
            mouse_y_address = module_base + 0xA2EC78
            
            pm.w_int(pm_proc, mouse_x_address, x_coord)
            pm.w_int(pm_proc, mouse_y_address, y_coord)

            print(f"Cursor movido a: ({x_coord}, {y_coord})")

            # Dibujar línea en pantalla
            screen.fill((0, 0, 0))  # Limpiar la pantalla
            pygame.draw.line(screen, (255, 0, 0), (400, 300), (x_coord, y_coord), 2)  # Dibuja la línea
            pygame.display.flip()  # Actualiza la pantalla
        else:
            print("No se pudieron leer las direcciones de las coordenadas.")
    else:
        print(f"No se encontró ninguna entidad con el Job ID {target_job_id}.")

    # Un pequeño retraso para no sobrecargar el CPU
    time.sleep(0.1)  # Puedes ajustar el tiempo de espera si lo deseas

# Cerrar Pygame
pygame.quit()
