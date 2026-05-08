import numpy as np
from ctypes import *
from ctypes.wintypes import *
from mss import mss

# Define las constantes y tipos necesarios
GENERIC_READ = 0x80000000
GENERIC_WRITE = 0x40000000
FILE_SHARE_READ = 0x00000001
FILE_SHARE_WRITE = 0x00000002
OPEN_EXISTING = 3
INVALID_HANDLE_VALUE = -1
USHORT = c_ushort

# Macro de traducción CTL_CODE
def CTL_CODE(DeviceType, Function, Method, Access):
    return (DeviceType << 16) | (Access << 14) | (Function << 2) | Method

# Clase Capture
class Capture:
    def __init__(self, x, y, xfov, yfov):
        self.x = x
        self.y = y
        self.mss = mss()
        self.monitor = {'top': y, 'left': x, 'width': xfov, 'height': yfov}
        self.xfov = xfov
        self.yfov = yfov

        self.hDriver = windll.kernel32.CreateFileA(b"\\\\.\\Oykyo",
                                                    GENERIC_READ | GENERIC_WRITE,
                                                    FILE_SHARE_READ | FILE_SHARE_WRITE,
                                                    None,
                                                    OPEN_EXISTING,
                                                    0,
                                                    None)
        if self.hDriver == INVALID_HANDLE_VALUE:
            print("Failed to open driver.")

    def __del__(self):
        if self.hDriver != INVALID_HANDLE_VALUE:
            windll.kernel32.CloseHandle(self.hDriver)

    def get_screen(self):
        screenshot = self.mss.grab(self.monitor)
        return np.array(screenshot)
    
    def update_resolution(self, xfov, yfov):
        self.xfov = xfov
        self.yfov = yfov
        self.monitor['width'] = xfov
        self.monitor['height'] = yfov
