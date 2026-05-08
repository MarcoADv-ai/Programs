import sys
import time
from termcolor import colored

def getMouse():
    try:
        print("[+] Device found!")
        time.sleep(5)
    except DeviceNotFoundError as e:
        print(e)
        sys.exit()
    return None

mouse = getMouse()
