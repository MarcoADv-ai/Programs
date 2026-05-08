from ctypes import *
from ctypes.wintypes import *
 
# Define necessary constants and types
GENERIC_READ = 0x80000000
GENERIC_WRITE = 0x40000000
FILE_SHARE_READ = 0x00000001
FILE_SHARE_WRITE = 0x00000002
OPEN_EXISTING = 3
INVALID_HANDLE_VALUE = -1
USHORT = c_ushort
 
# CTL_CODE Macro Translation
def CTL_CODE(DeviceType, Function, Method, Access):
    return (DeviceType << 16) | (Access << 14) | (Function << 2) | Method
 
# MouseController Class
class MouseController:
    def __init__(self):
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
 
    def move_mouse(self, x, y):
        return self.send_mouse_event(x, y, 0)
 
    def click(self, button):
        return self.send_mouse_event(0, 0, button)
 
    def send_mouse_event(self, x, y, button):
        if self.hDriver == INVALID_HANDLE_VALUE:
            return False
        
        class MOUSE_REQUEST(Structure):
            _fields_ = [("x", c_int),
                        ("y", c_int),
                        ("buttonFlags", USHORT)]
        
        request = MOUSE_REQUEST(x, y, button)
        return windll.kernel32.DeviceIoControl(self.hDriver,
                                               CTL_CODE(34, 73142, 0, 0),
                                               byref(request),
                                               sizeof(request),
                                               None,
                                               0,
                                               byref(c_ulong()),
                                               None)
 
if __name__ == "__main__":
    mouse = MouseController()
    if mouse.move_mouse(100, 100):
        print("Mouse moved successfully.")
    
    if mouse.click(1):  # Assuming 1 represents left button down in your driver
        print("Mouse left button clicked.")
        mouse.click(2)  # Assuming 2 represents left button up