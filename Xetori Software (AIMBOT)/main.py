import ctypes
import os
from xet0ri import Xet0ri
from settings import Settings
from mouse_controller import MouseController
from termcolor import colored

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
        print("\033[92m" + "Successful connection to the xet0ri" + "\033[0m")
        ctypes.windll.kernel32.CloseHandle(hDriver)
        return True

class Main:
    def __init__(self):
        self.settings = Settings()
        self.monitor_width, self.monitor_height = self.get_monitor_resolution()
        self.CENTER_X, self.CENTER_Y = self.monitor_width // 2, self.monitor_height // 2
        self.XFOV = self.settings.get_int('Xet0riA1M', 'xFov')
        self.YFOV = self.settings.get_int('Xet0riA1M', 'yFov')
        self.mouse = MouseController()
        self.Xet0ri = Xet0ri(self.CENTER_X - self.XFOV // 2, self.CENTER_Y - self.YFOV // 2, self.XFOV, self.YFOV, self.mouse)

        if not verificar_conexion_driver():
            print("\033[91m" + "¡Error! The connection to the xet0ri could not be opened" + "\033[0m")
            exit()

    def get_monitor_resolution(self):
        width = self.settings.get_int('Capture', 'width')
        height = self.settings.get_int('Capture', 'height')
        return width, height

    def better_cmd(self, width, height):
        hwnd = ctypes.windll.kernel32.GetConsoleWindow()
        if hwnd:
            style = ctypes.windll.user32.GetWindowLongW(hwnd, -16)
            style &= -262145
            style &= -65537
            ctypes.windll.user32.SetWindowLongW(hwnd, -16, style)
        STD_OUTPUT_HANDLE_ID = ctypes.c_ulong(4294967285)
        windll = ctypes.windll.kernel32
        handle = windll.GetStdHandle(STD_OUTPUT_HANDLE_ID)
        rect = ctypes.wintypes.SMALL_RECT(0, 0, width - 1, height - 1)
        windll.SetConsoleScreenBufferSize(handle, ctypes.wintypes._COORD(width, height))
        windll.SetConsoleWindowInfo(handle, ctypes.c_int(True), ctypes.pointer(rect))

    def info(self):
        os.system('cls')
        print(colored('''
                                     __  __  _____   _____    ___    ____    ___ 
                                     \ \/ / | ____| |_   _|  / _ \  |  _ \  |_ _|
                                      \  /  | _|      | |   | | | | | |_) |  | | 
                                      /  \  | |___    | |   | |_| | |  _ <   | | 
                                     /_/\_\ |_____|   |_|    \___/  |_| \_\ |___|
                                             
                                              v1.0 Und3t3ct3d''', 'cyan'))
        self.Xet0ri.print_settings()

    def run(self):
        self.better_cmd(120, 30)
        self.info()
        self.Xet0ri.listen()

if __name__ == '__main__':
    Main().run()
