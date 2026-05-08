import ctypes
import struct
import threading
import time
import pygetwindow as gw
import keyboard
import psutil
from ctypes import wintypes
from dearpygui import dearpygui as dpg

# Opcional: Define una variable de depuración
DEBUG = False  # Cambiado a True para activar mensajes de depuración

# Variables para almacenar los ID y PIDs de las ventanas seleccionadas
window_ids = {'Priest': None, 'Soul Linker': None, 'Paladin': None, 'Creator': None}
window_pids = {'Priest': None, 'Soul Linker': None, 'Paladin': None, 'Creator': None}


# Variables para almacenar las coordenadas del mouse
friend_mouse_coordinates = {
    'Priest': {'x': None, 'y': None},
    'Soul Linker': {'x': None, 'y': None},
    'Paladin': {'x': None, 'y': None},
    'Creator': {'x': None, 'y': None}
}

# Variable de control para el bucle Start/Stop
is_running = {'Priest': False, 'Soul Linker': False, 'Paladin': False, 'Creator': False}


PROCESS_ALL_ACCESS = (0x000F0000 | 0x00100000 | 0xFFF)

# Definición de MODULEENTRY32
class MODULEENTRY32(ctypes.Structure):
    _fields_ = [
        ('dwSize', wintypes.DWORD),
        ('th32ModuleID', wintypes.DWORD),
        ('th32ProcessID', wintypes.DWORD),
        ('GlblcntUsage', wintypes.DWORD),
        ('ProccntUsage', wintypes.DWORD),
        ('modBaseAddr', ctypes.POINTER(ctypes.c_byte)),
        ('modBaseSize', wintypes.DWORD),
        ('hModule', wintypes.HMODULE),
        ('szModule', ctypes.c_char * 256),
        ('szExePath', ctypes.c_char * wintypes.MAX_PATH)
    ]

# Diccionario para almacenar los widgets de entrada de skills
skill_entries = {
    'Priest': {
        'skill1_id': None,
        'skill1_lvl': None,
        'skill2_id': None,
        'skill2_lvl': None
    },
    'Soul Linker': {
        'skill1_id': None,
        'skill1_lvl': None,
        'skill2_id': None,
        'skill2_lvl': None
    },
    'Paladin': {
        'skill1_id': None,
        'skill1_lvl': None,
        'skill2_id': None,
        'skill2_lvl': None
    },
        'Creator': {
        'skill1_id': None,
        'skill1_lvl': None,
        'skill2_id': None,
        'skill2_lvl': None
    }
}

# Función para listar todos los módulos de un proceso
def list_process_modules(pid):
    TH32CS_SNAPMODULE = 0x00000008
    TH32CS_SNAPMODULE32 = 0x00000010

    CreateToolhelp32Snapshot = ctypes.windll.kernel32.CreateToolhelp32Snapshot
    Module32First = ctypes.windll.kernel32.Module32First
    Module32Next = ctypes.windll.kernel32.Module32Next
    CloseHandle = ctypes.windll.kernel32.CloseHandle

    hModuleSnap = CreateToolhelp32Snapshot(TH32CS_SNAPMODULE | TH32CS_SNAPMODULE32, pid)
    if hModuleSnap == -1:
        if DEBUG:
            print("Error al crear snapshot de módulos.")
        return []

    me32 = MODULEENTRY32()
    me32.dwSize = ctypes.sizeof(MODULEENTRY32)

    modules = []

    if not Module32First(hModuleSnap, ctypes.byref(me32)):
        if DEBUG:
            print("Error al obtener el primer módulo.")
        CloseHandle(hModuleSnap)
        return modules

    while True:
        try:
            module_name = me32.szModule.decode('utf-8').rstrip('\x00')
            exe_path = me32.szExePath.decode('utf-8').rstrip('\x00')
            mod_base = ctypes.addressof(me32.modBaseAddr.contents)
            modules.append((module_name, exe_path, mod_base))
            if DEBUG:
                print(f"Módulo encontrado: {module_name} ({exe_path}) en {hex(mod_base)}")
        except Exception as e:
            if DEBUG:
                print(f"Error al procesar un módulo: {e}")
        if not Module32Next(hModuleSnap, ctypes.byref(me32)):
            break

    CloseHandle(hModuleSnap)
    return modules

# Función para obtener el PID de una ventana usando la API de Windows
def get_window_pid(hwnd):
    _GetWindowThreadProcessId = ctypes.windll.user32.GetWindowThreadProcessId
    _GetWindowThreadProcessId.argtypes = [wintypes.HWND, ctypes.POINTER(wintypes.DWORD)]
    _GetWindowThreadProcessId.restype = wintypes.DWORD

    pid = wintypes.DWORD()
    _GetWindowThreadProcessId(hwnd, ctypes.byref(pid))
    return pid.value

# Función para obtener el nombre del proceso usando el PID
def get_process_name(pid):
    try:
        process = psutil.Process(pid)
        return process.name()
    except (psutil.NoSuchProcess, psutil.AccessDenied, psutil.ZombieProcess):
        if DEBUG:
            print(f"No se pudo obtener el nombre del proceso para PID {pid}.")
        return None

# Función para abrir el proceso y obtener el handle usando el PID
def open_process(pid):
    try:
        handle = ctypes.windll.kernel32.OpenProcess(PROCESS_ALL_ACCESS, False, pid)
        if not handle:
            raise ctypes.WinError()
        return handle
    except Exception as e:
        if DEBUG:
            print(f"Error al abrir el proceso con PID {pid}: {e}")
        return None

# Función para obtener la base del módulo del proceso
def get_module_base_ctypes(pid, module_name):
    modules = list_process_modules(pid)
    for mod_name, exe_path, mod_base in modules:
        if mod_name.lower() == module_name.lower():
            if DEBUG:
                print(f"Base del módulo '{module_name}' encontrada para PID {pid}: {hex(mod_base)}")
            return mod_base
    if DEBUG:
        print(f"Módulo '{module_name}' no encontrado para PID {pid}.")
    return None

# Función para leer memoria desde el proceso
def read_memory(handle, address, size):
    data = ctypes.create_string_buffer(size)
    bytes_read = ctypes.c_size_t()
    if ctypes.windll.kernel32.ReadProcessMemory(handle, ctypes.c_void_p(address), data, size, ctypes.byref(bytes_read)):
        if size == 1:
            return struct.unpack('B', data.raw)[0]  # Leer como byte (1 byte)
        elif size == 4:
            return struct.unpack('I', data.raw)[0]  # Leer como entero sin signo (4 bytes)
        else:
            if DEBUG:
                print(f"Tamaño de lectura no soportado: {size}")
            return None
    else:
        if DEBUG:
            print(f"Error al leer la memoria en la dirección {hex(address)}")
        return None

# Función para escribir memoria en el proceso
def write_memory(handle, address, value, size=4):
    if size == 1:
        data = struct.pack('B', value)  # Escribir como byte (1 byte)
    elif size == 4:
        data = struct.pack('I', value)  # Escribir como entero sin signo (4 bytes)
    else:
        if DEBUG:
            print("Tamaño de escritura no soportado")
        return False
    bytes_written = ctypes.c_size_t()
    if ctypes.windll.kernel32.WriteProcessMemory(handle, ctypes.c_void_p(address), data, len(data), ctypes.byref(bytes_written)):
        if DEBUG:
            print(f"Escritura de memoria exitosa en la dirección {hex(address)} con valor {value}")
        return True
    else:
        print(f"Error al escribir en la memoria en la dirección {hex(address)}")
        return False

# Función para capturar coordenadas del Friend
def capture_friend_coordinates(window_key):
    print(f"Presiona la barra espaciadora para capturar las coordenadas del Friend para {window_key}...")
    keyboard.wait('space')
    # Obtener el PID del proceso seleccionado
    if window_pids[window_key]:
        pid = window_pids[window_key]
        game_process_name = get_process_name(pid)
        if game_process_name:
            # Necesitas conocer el nombre exacto del módulo, por ejemplo 'HoneyRO.exe'
            module_name = game_process_name  # Ajusta esto si el módulo tiene otro nombre
            module_base = get_module_base_ctypes(pid, module_name)
            if module_base is not None:
                handle = open_process(pid)
                if handle:
                    try:
                        # Direcciones relativas al módulo del juego
                        mouse_x_offset = 0xA2EC74  # Ajusta según sea necesario
                        mouse_y_offset = 0xA2EC78  # Ajusta según sea necesario

                        mouse_x_address = module_base + mouse_x_offset
                        mouse_y_address = module_base + mouse_y_offset

                        if DEBUG:
                            print(f"Direcciones calculadas para captura - X: {hex(mouse_x_address)}, Y: {hex(mouse_y_address)}")

                        # Leer los valores en las direcciones especificadas (4 bytes)
                        valorxmouse = read_memory(handle, mouse_x_address, 4)
                        valorymouse = read_memory(handle, mouse_y_address, 4)
                        if valorxmouse is not None and valorymouse is not None:
                            friend_mouse_coordinates[window_key]['x'] = valorxmouse
                            friend_mouse_coordinates[window_key]['y'] = valorymouse
                            print(f"Coordenadas Friend para {window_key} - X: {valorxmouse}, Y: {valorymouse}")
                        else:
                            print(f"No se pudieron leer correctamente las coordenadas del Friend para {window_key}.")
                    except Exception as e:
                        print(f"Error al leer las coordenadas del mouse para {window_key}: {e}")
                    finally:
                        ctypes.windll.kernel32.CloseHandle(handle)
                else:
                    print(f"No se pudo abrir el proceso correctamente para {window_key}.")
            else:
                print(f"No se pudo obtener la base del módulo correctamente para {window_key}.")
        else:
            print(f"No se pudo obtener el nombre del proceso para {window_key}.")
    else:
        print(f"No se ha seleccionado ninguna ventana para {window_key}.")

# Nueva función para escribir coordenadas Friend en la memoria
def write_friend_coordinates_mem(window_key):
    if window_pids[window_key]:
        pid = window_pids[window_key]
        game_process_name = get_process_name(pid)
        if game_process_name:
            # Necesitas conocer el nombre exacto del módulo, por ejemplo 'HoneyRO.exe'
            module_name = game_process_name  # Ajusta esto si el módulo tiene otro nombre
            module_base = get_module_base_ctypes(pid, module_name)
            if module_base is not None:
                handle = open_process(pid)
                if handle:
                    try:
                        # Direcciones relativas para escribir las coordenadas del Friend
                        mouse_x_offset = 0xA2EC74  # Asegúrate de que este offset sea correcto
                        mouse_y_offset = 0xA2EC78  # Asegúrate de que este offset sea correcto

                        mouse_x_address = module_base + mouse_x_offset
                        mouse_y_address = module_base + mouse_y_offset

                        if DEBUG:
                            print(f"Direcciones calculadas - X: {hex(mouse_x_address)}, Y: {hex(mouse_y_address)}")

                        # Usar las coordenadas capturadas del Friend
                        if friend_mouse_coordinates[window_key]['x'] is not None and friend_mouse_coordinates[window_key]['y'] is not None:
                            x_coord = friend_mouse_coordinates[window_key]['x']
                            y_coord = friend_mouse_coordinates[window_key]['y']
                            print(f"Escribiendo coordenadas Friend para {window_key} - X: {x_coord}, Y: {y_coord}")
                            if write_memory(handle, mouse_x_address, x_coord, size=4):
                                if DEBUG:
                                    print(f"Escritura exitosa de X en {hex(mouse_x_address)} para {window_key}")
                            else:
                                print(f"Error al escribir la coordenada X en {hex(mouse_x_address)} para {window_key}")
                            if write_memory(handle, mouse_y_address, y_coord, size=4):
                                if DEBUG:
                                    print(f"Escritura exitosa de Y en {hex(mouse_y_address)} para {window_key}")
                            else:
                                print(f"Error al escribir la coordenada Y en {hex(mouse_y_address)} para {window_key}")
                            print(f"Cursor movido a: ({x_coord}, {y_coord}) para {window_key}")
                        else:
                            print(f"No hay coordenadas capturadas para el Friend para {window_key}.")
                    except Exception as e:
                        print(f"Error al escribir las coordenadas del Friend para {window_key}: {e}")
                    finally:
                        ctypes.windll.kernel32.CloseHandle(handle)
                else:
                    print(f"No se pudo abrir el proceso correctamente para {window_key}.")
            else:
                print(f"No se pudo obtener la base del módulo correctamente para {window_key}.")
        else:
            print(f"No se pudo obtener el nombre del proceso para {window_key}.")
    else:
        print(f"No se ha seleccionado ninguna ventana para {window_key}.")

# Funciones para escribir skillID, skilltype y byte values para cada personaje
def write_skillid_skilltype_and_byte_values_priest():
    while is_running['Priest']:
        if window_pids['Priest']:
            pid = window_pids['Priest']
            game_process_name = get_process_name(pid)
            if game_process_name:
                module_name = game_process_name  # Ajusta si el nombre del módulo es diferente
                module_base = get_module_base_ctypes(pid, module_name)
                if module_base is not None:
                    handle = open_process(pid)
                    if handle:
                        try:
                            # Leer Skill 1 ID y Level desde la GUI
                            skill1_id_str = dpg.get_value("Priest_skill1_id")
                            skill1_lvl_str = dpg.get_value("Priest_skill1_lvl")
                            skill2_id_str = dpg.get_value("Priest_skill2_id")
                            skill2_lvl_str = dpg.get_value("Priest_skill2_lvl")

                            if DEBUG:
                                print(f"[Priest] Skill1 ID: {skill1_id_str}, Skill1 Level: {skill1_lvl_str}")
                                print(f"[Priest] Skill2 ID: {skill2_id_str}, Skill2 Level: {skill2_lvl_str}")

                            # Validar y convertir a enteros
                            if None in (skill1_id_str, skill1_lvl_str, skill2_id_str, skill2_lvl_str):
                                print("Error: Uno o más campos de habilidad están vacíos en Priest.")
                                continue

                            skill1_id = int(skill1_id_str)
                            skill1_lvl = int(skill1_lvl_str)
                            skill2_id = int(skill2_id_str)
                            skill2_lvl = int(skill2_lvl_str)

                            # SkillID específico para Priest
                            base_address_skillid = 0x00A43F44
                            offset_skillid = 0x3FC
                            offset_skilltype = 0x3F8
                            offset_skilllvl = 0x404

                            pointer_address_skillid = module_base + base_address_skillid
                            pointer_value = read_memory(handle, pointer_address_skillid, 4)
                            if pointer_value is not None:
                                final_address_skillid = pointer_value + offset_skillid
                                final_address_skilltype = pointer_value + offset_skilltype
                                final_address_skilllvl = pointer_value + offset_skilllvl

                                write_friend_coordinates_mem('Priest')

                                # Escribir Skill 1
                                write_memory(handle, final_address_skillid, skill1_id, size=4)
                                write_memory(handle, final_address_skilltype, 4, size=4)
                                write_memory(handle, final_address_skilllvl, skill1_lvl, size=4)

                                # Activar la habilidad
                                controlclick_address_byte = module_base + 0xA2EC82  # Ajusta el offset según sea necesario
                                write_memory(handle, controlclick_address_byte, 1, size=1)
                                time.sleep(0.5)
                                write_memory(handle, controlclick_address_byte, 0, size=1)
                                time.sleep(0.1)

                                # Escribir Skill 2
                                write_memory(handle, final_address_skillid, skill2_id, size=4)
                                write_memory(handle, final_address_skilltype, 4, size=4)
                                write_memory(handle, final_address_skilllvl, skill2_lvl, size=4)

                                # Activar la habilidad
                                write_memory(handle, controlclick_address_byte, 1, size=1)
                                time.sleep(0.5)
                                write_memory(handle, controlclick_address_byte, 0, size=1)

                        except ValueError:
                            print(f"Error: Asegúrate de ingresar números válidos para los IDs y niveles de habilidades en Priest.")
                        except Exception as e:
                            print(f"Error al escribir los valores para Priest: {e}")
                        finally:
                            ctypes.windll.kernel32.CloseHandle(handle)
                    else:
                        print(f"No se pudo abrir el proceso correctamente para Priest.")
                else:
                    print(f"No se pudo obtener la base del módulo correctamente para Priest.")
            else:
                print(f"No se ha seleccionado ninguna ventana para Priest.")
        else:
            print(f"No se ha seleccionado ninguna ventana para Priest.")
        time.sleep(1)

def write_skillid_skilltype_and_byte_values_soul_linker():
    tag_key = "SoulLinker"  # Sin espacio para etiquetas
    while is_running['Soul Linker']:
        if window_pids['Soul Linker']:
            pid = window_pids['Soul Linker']
            game_process_name = get_process_name(pid)
            if game_process_name:
                module_name = game_process_name  # Ajusta si el nombre del módulo es diferente
                module_base = get_module_base_ctypes(pid, module_name)
                if module_base is not None:
                    handle = open_process(pid)
                    if handle:
                        try:
                            # Leer Skill 1 ID y Level desde la GUI
                            skill1_id_str = dpg.get_value(f"{tag_key}_skill1_id")
                            skill1_lvl_str = dpg.get_value(f"{tag_key}_skill1_lvl")
                            skill2_id_str = dpg.get_value(f"{tag_key}_skill2_id")
                            skill2_lvl_str = dpg.get_value(f"{tag_key}_skill2_lvl")

                            if DEBUG:
                                print(f"[Soul Linker] Skill1 ID: {skill1_id_str}, Skill1 Level: {skill1_lvl_str}")
                                print(f"[Soul Linker] Skill2 ID: {skill2_id_str}, Skill2 Level: {skill2_lvl_str}")

                            # Validar y convertir a enteros
                            if None in (skill1_id_str, skill1_lvl_str, skill2_id_str, skill2_lvl_str):
                                print("Error: Uno o más campos de habilidad están vacíos en Soul Linker.")
                                continue

                            skill1_id = int(skill1_id_str)
                            skill1_lvl = int(skill1_lvl_str)
                            skill2_id = int(skill2_id_str)
                            skill2_lvl = int(skill2_lvl_str)

                            # SkillID específico para Soul Linker
                            base_address_skillid = 0x00A43F44
                            offset_skillid = 0x3FC
                            offset_skilltype = 0x3F8
                            offset_skilllvl = 0x404

                            pointer_address_skillid = module_base + base_address_skillid
                            pointer_value = read_memory(handle, pointer_address_skillid, 4)
                            if pointer_value is not None:
                                final_address_skillid = pointer_value + offset_skillid
                                final_address_skilltype = pointer_value + offset_skilltype
                                final_address_skilllvl = pointer_value + offset_skilllvl

                                write_friend_coordinates_mem('Soul Linker')

                                # Escribir Skill 1
                                write_memory(handle, final_address_skillid, skill1_id, size=4)
                                write_memory(handle, final_address_skilltype, 4, size=4)
                                write_memory(handle, final_address_skilllvl, skill1_lvl, size=4)

                                # Activar la habilidad
                                controlclick_address_byte = module_base + 0xA2EC82  # Ajusta el offset según sea necesario
                                write_memory(handle, controlclick_address_byte, 1, size=1)
                                time.sleep(0.2)
                                write_memory(handle, controlclick_address_byte, 0, size=1)
                                time.sleep(0.4)

                                # Escribir Skill 2
                                write_memory(handle, final_address_skillid, skill2_id, size=4)
                                write_memory(handle, final_address_skilltype, 4, size=4)
                                write_memory(handle, final_address_skilllvl, skill2_lvl, size=4)

                                # Activar la habilidad
                                write_memory(handle, controlclick_address_byte, 1, size=1)
                                time.sleep(0.5)
                                write_memory(handle, controlclick_address_byte, 0, size=1)

                        except ValueError:
                            print(f"Error: Asegúrate de ingresar números válidos para los IDs y niveles de habilidades en Soul Linker.")
                        except Exception as e:
                            print(f"Error al escribir los valores para Soul Linker: {e}")
                        finally:
                            ctypes.windll.kernel32.CloseHandle(handle)
                    else:
                        print(f"No se pudo abrir el proceso correctamente para Soul Linker.")
                else:
                    print(f"No se pudo obtener la base del módulo correctamente para Soul Linker.")
            else:
                print(f"No se pudo obtener el nombre del proceso para Soul Linker.")
        else:
            print(f"No se ha seleccionado ninguna ventana para Soul Linker.")
        time.sleep(1)

def write_skillid_skilltype_and_byte_values_paladin():
    while is_running['Paladin']:
        if window_pids['Paladin']:
            pid = window_pids['Paladin']
            game_process_name = get_process_name(pid)
            if game_process_name:
                module_name = game_process_name  # Ajusta si el nombre del módulo es diferente
                module_base = get_module_base_ctypes(pid, module_name)
                if module_base is not None:
                    handle = open_process(pid)
                    if handle:
                        try:
                            # Leer Skill 1 ID y Level desde la GUI
                            skill1_id_str = dpg.get_value("Paladin_skill1_id")
                            skill1_lvl_str = dpg.get_value("Paladin_skill1_lvl")
                            skill2_id_str = dpg.get_value("Paladin_skill2_id")
                            skill2_lvl_str = dpg.get_value("Paladin_skill2_lvl")

                            if DEBUG:
                                print(f"[Paladin] Skill1 ID: {skill1_id_str}, Skill1 Level: {skill1_lvl_str}")
                                print(f"[Paladin] Skill2 ID: {skill2_id_str}, Skill2 Level: {skill2_lvl_str}")

                            # Validar y convertir a enteros
                            if None in (skill1_id_str, skill1_lvl_str, skill2_id_str, skill2_lvl_str):
                                print("Error: Uno o más campos de habilidad están vacíos en Paladin.")
                                continue

                            skill1_id = int(skill1_id_str)
                            skill1_lvl = int(skill1_lvl_str)
                            skill2_id = int(skill2_id_str)
                            skill2_lvl = int(skill2_lvl_str)

                            # SkillID específico para Paladin
                            base_address_skillid = 0x00A43F44
                            offset_skillid = 0x3FC
                            offset_skilltype = 0x3F8
                            offset_skilllvl = 0x404

                            pointer_address_skillid = module_base + base_address_skillid
                            pointer_value = read_memory(handle, pointer_address_skillid, 4)
                            if pointer_value is not None:
                                final_address_skillid = pointer_value + offset_skillid
                                final_address_skilltype = pointer_value + offset_skilltype
                                final_address_skilllvl = pointer_value + offset_skilllvl

                                write_friend_coordinates_mem('Paladin')

                                # Escribir Skill 1
                                write_memory(handle, final_address_skillid, skill1_id, size=4)
                                write_memory(handle, final_address_skilltype, 4, size=4)
                                write_memory(handle, final_address_skilllvl, skill1_lvl, size=4)

                                # Activar la habilidad
                                controlclick_address_byte = module_base + 0xA2EC82  # Ajusta el offset según sea necesario
                                write_memory(handle, controlclick_address_byte, 1, size=1)
                                time.sleep(0.1)
                                write_memory(handle, controlclick_address_byte, 0, size=1)

                                # Escribir Skill 2
                                write_memory(handle, final_address_skillid, skill2_id, size=4)
                                write_memory(handle, final_address_skilltype, 4, size=4)
                                write_memory(handle, final_address_skilllvl, skill2_lvl, size=4)

                                # Activar la habilidad
                                write_memory(handle, controlclick_address_byte, 1, size=1)
                                time.sleep(0.1)
                                write_memory(handle, controlclick_address_byte, 0, size=1)

                        except ValueError:
                            print(f"Error: Asegúrate de ingresar números válidos para los IDs y niveles de habilidades en Paladin.")
                        except Exception as e:
                            print(f"Error al escribir los valores para Paladin: {e}")
                        finally:
                            ctypes.windll.kernel32.CloseHandle(handle)
                    else:
                        print(f"No se pudo abrir el proceso correctamente para Paladin.")
                else:
                    print(f"No se pudo obtener la base del módulo correctamente para Paladin.")
            else:
                print(f"No se pudo obtener el nombre del proceso para Paladin.")
        else:
            print(f"No se ha seleccionado ninguna ventana para Paladin.")
        time.sleep(1)

def write_skillid_skilltype_and_byte_values_creator():
    while is_running['Creator']:
        if window_pids['Creator']:
            pid = window_pids['Creator']
            game_process_name = get_process_name(pid)
            if game_process_name:
                module_name = game_process_name  # Ajusta si el nombre del módulo es diferente
                module_base = get_module_base_ctypes(pid, module_name)
                if module_base is not None:
                    handle = open_process(pid)
                    if handle:
                        try:
                            # Leer Skill 1 ID y Level desde la GUI
                            skill1_id_str = dpg.get_value("Creator_skill1_id")
                            skill1_lvl_str = dpg.get_value("Creator_skill1_lvl")
                            skill2_id_str = dpg.get_value("Creator_skill2_id")
                            skill2_lvl_str = dpg.get_value("Creator_skill2_lvl")

                            if DEBUG:
                                print(f"[Creator] Skill1 ID: {skill1_id_str}, Skill1 Level: {skill1_lvl_str}")
                                print(f"[Creator] Skill2 ID: {skill2_id_str}, Skill2 Level: {skill2_lvl_str}")

                            # Validar y convertir a enteros
                            if None in (skill1_id_str, skill1_lvl_str, skill2_id_str, skill2_lvl_str):
                                print("Error: Uno o más campos de habilidad están vacíos en Paladin.")
                                continue

                            skill1_id = int(skill1_id_str)
                            skill1_lvl = int(skill1_lvl_str)
                            skill2_id = int(skill2_id_str)
                            skill2_lvl = int(skill2_lvl_str)

                            # SkillID específico para Paladin
                            base_address_skillid = 0x00A43F44
                            offset_skillid = 0x3FC
                            offset_skilltype = 0x3F8
                            offset_skilllvl = 0x404

                            pointer_address_skillid = module_base + base_address_skillid
                            pointer_value = read_memory(handle, pointer_address_skillid, 4)
                            if pointer_value is not None:
                                final_address_skillid = pointer_value + offset_skillid
                                final_address_skilltype = pointer_value + offset_skilltype
                                final_address_skilllvl = pointer_value + offset_skilllvl

                                write_friend_coordinates_mem('Creator')

                                # Escribir Skill 1
                                write_memory(handle, final_address_skillid, skill1_id, size=4)
                                write_memory(handle, final_address_skilltype, 4, size=4)
                                write_memory(handle, final_address_skilllvl, skill1_lvl, size=4)

                                # Activar la habilidad
                                controlclick_address_byte = module_base + 0xA2EC82  # Ajusta el offset según sea necesario
                                write_memory(handle, controlclick_address_byte, 1, size=1)
                                time.sleep(0.1)
                                write_memory(handle, controlclick_address_byte, 0, size=1)

                                # Escribir Skill 2
                                write_memory(handle, final_address_skillid, skill2_id, size=4)
                                write_memory(handle, final_address_skilltype, 4, size=4)
                                write_memory(handle, final_address_skilllvl, skill2_lvl, size=4)

                                # Activar la habilidad
                                write_memory(handle, controlclick_address_byte, 1, size=1)
                                time.sleep(0.1)
                                write_memory(handle, controlclick_address_byte, 0, size=1)

                        except ValueError:
                            print(f"Error: Asegúrate de ingresar números válidos para los IDs y niveles de habilidades en Paladin.")
                        except Exception as e:
                            print(f"Error al escribir los valores para Creator: {e}")
                        finally:
                            ctypes.windll.kernel32.CloseHandle(handle)
                    else:
                        print(f"No se pudo abrir el proceso correctamente para Paladin.")
                else:
                    print(f"No se pudo obtener la base del módulo correctamente para Paladin.")
            else:
                print(f"No se pudo obtener el nombre del proceso para Paladin.")
        else:
            print(f"No se ha seleccionado ninguna ventana para Paladin.")
        time.sleep(1)



# Función para manejar el botón Start/Stop para cada personaje
def toggle_start_stop(sender, app_data, user_data):
    window_key = user_data
    if not is_running[window_key]:
        is_running[window_key] = True
        dpg.set_value(f"{window_key.replace(' ', '')}_start_stop_button", "Stop")
        if window_key == 'Priest':
            threading.Thread(target=write_skillid_skilltype_and_byte_values_priest, daemon=True).start()
        elif window_key == 'Soul Linker':
            threading.Thread(target=write_skillid_skilltype_and_byte_values_soul_linker, daemon=True).start()
        elif window_key == 'Paladin':
            threading.Thread(target=write_skillid_skilltype_and_byte_values_paladin, daemon=True).start()
        elif window_key == 'Creator':
            threading.Thread(target=write_skillid_skilltype_and_byte_values_paladin, daemon=True).start()
    else:
        is_running[window_key] = False
        dpg.set_value(f"{window_key.replace(' ', '')}_start_stop_button", "Start")

# Función para capturar la ventana activa y su PID
def set_window_callback(sender, app_data, user_data):
    window_key = user_data
    print("Presiona la barra espaciadora para seleccionar la ventana activa...")
    keyboard.wait('space')
    active_window = gw.getActiveWindow()
    if active_window:
        pid = get_window_pid(active_window._hWnd)
        window_ids[window_key] = active_window
        window_pids[window_key] = pid
        tag_key = window_key.replace(' ', '')
        dpg.set_value(f"{tag_key}_label", f"WinID: {active_window.title} (PID: {pid})")
        print(f"Ventana seleccionada para {window_key}: {active_window.title} (PID: {pid})")

        # Listar módulos para verificar el nombre exacto (Opcional)
        if DEBUG:
            print(f"Listando módulos para PID {pid}...")
            modules = list_process_modules(pid)
            if modules:
                print(f"Módulos encontrados para PID {pid}:")
                for mod in modules:
                    print(f" - {mod[0]} ({mod[1]})")
            else:
                print(f"No se encontraron módulos para PID {pid}.")
    else:
        print("No se encontró ventana activa")


# Función para crear la sección de cada personaje
def create_section(title, key, default_skill1_id, default_skill1_lvl, default_skill2_id, default_skill2_lvl):
    # Reemplazar espacios por guiones bajos en la clave para usar en tags
    tag_key = key.replace(' ', '')
    with dpg.collapsing_header(label=title, default_open=True):
        # Mostrar WinID y PID
        dpg.add_text(default_value="WinID: None (PID: None)", tag=f"{tag_key}_label")

        # Botón para establecer la ventana
        dpg.add_button(label="Set", callback=set_window_callback, user_data=key)

        # Botón para capturar coordenadas Friend
        dpg.add_button(label=f"Coordenadas Friend ({key})", callback=lambda s, a, u: capture_friend_coordinates(u), user_data=key)

        # Campos de entrada para Skill 1
        with dpg.group(horizontal=True):
            dpg.add_text("Skill 1 ID:")
            dpg.add_input_text(tag=f"{tag_key}_skill1_id", default_value=str(default_skill1_id), width=100)
            dpg.add_text("Skill 1 Level:")
            dpg.add_input_text(tag=f"{tag_key}_skill1_lvl", default_value=str(default_skill1_lvl), width=100)

        # Campos de entrada para Skill 2
        with dpg.group(horizontal=True):
            dpg.add_text("Skill 2 ID:")
            dpg.add_input_text(tag=f"{tag_key}_skill2_id", default_value=str(default_skill2_id), width=100)
            dpg.add_text("Skill 2 Level:")
            dpg.add_input_text(tag=f"{tag_key}_skill2_lvl", default_value=str(default_skill2_lvl), width=100)

        # Botón Start/Stop
        dpg.add_button(label="Start", tag=f"{tag_key}_start_stop_button", callback=toggle_start_stop, user_data=key)

# Función para cerrar correctamente DearPyGui
def on_close(sender, app_data):
    dpg.stop_dearpygui()

# Inicialización de la ventana principal de DearPyGui
dpg.create_context()
dpg.create_viewport(title='AutoBuff T1 KING', width=600, height=800)

with dpg.window(label="Configuración de Autobuff", tag="primary_window", width=580, height=780):
    # Crear secciones para cada personaje
    create_section(
        title="Priest Config",
        key="Priest",
        default_skill1_id=72,
        default_skill1_lvl=1,
        default_skill2_id=72,
        default_skill2_lvl=1
    )
    create_section(
        title="Soul Linker Config",
        key="Soul Linker",
        default_skill1_id=464,
        default_skill1_lvl=3,
        default_skill2_id=462,
        default_skill2_lvl=7
    )
    create_section(
        title="Paladin Config",
        key="Paladin",
        default_skill1_id=256,
        default_skill1_lvl=5,
        default_skill2_id=256,
        default_skill2_lvl=5
    )
    create_section(
    title="Creator Config",
    key="Creator",
    default_skill1_id=479,
    default_skill1_lvl=5,
    default_skill2_id=479,
    default_skill2_lvl=5
)


dpg.setup_dearpygui()
dpg.show_viewport()

# Establecer la ventana primaria usando el tag en lugar del label
dpg.set_primary_window("primary_window", True)

# Configurar la función de cierre
dpg.set_exit_callback(on_close)

# Implementación del bucle de renderizado personalizado para limitar a 25 FPS
def main_loop():
    fps = 25
    frame_duration = 1.0 / fps
    while dpg.is_dearpygui_running():
        start_time = time.time()
        dpg.render_dearpygui_frame()
        elapsed = time.time() - start_time
        time_to_sleep = frame_duration - elapsed
        if time_to_sleep > 0:
            time.sleep(time_to_sleep)
        else:
            if DEBUG:
                print(f"Frame skipped! Elapsed time: {elapsed:.4f} segundos.")

# Iniciar el bucle de renderizado personalizado
main_loop()

dpg.destroy_context()
