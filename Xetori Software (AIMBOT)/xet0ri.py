import cv2
import numpy as np
import win32api
import sys
import os  
from capture import Capture
from settings import Settings
import time
from termcolor import colored

class Xet0ri:
    def __init__(self, x, y, xfov, yfov, mouse):
        self.settings = Settings()
        self.grabber = Capture(x, y, xfov, yfov)
        self.configure()
        self.LOWER_COLOR, self.UPPER_COLOR = self.get_colors()
        self.toggled = False
        self.paused = False  # Agregar estado pausado
        self.mouse = mouse  # Asignar el objeto MouseController

    def configure(self):
        self.xspeed = lambda: self.settings.get_float('Xet0riA1M', 'x_speed')
        self.yspeed = lambda: self.settings.get_float('Xet0riA1M', 'y_speed')
        self.Xet0riA1M_KEY = int(self.settings.get('Xet0riA1M', 'toggle_key'), 16)
        self.PAUSE_RESUME_KEY = int(self.settings.get('Xet0riA1M', 'pause_resume_key'), 16)
        self.RELOAD_KEY = int(self.settings.get('Controls', 'reload_key'), 16)  # Agregar tecla de recarga
        self.OFFSET_X = float(self.settings.get('Xet0riA1M', '0ffs3t_X'))
        self.OFFSET_Y = float(self.settings.get('Xet0riA1M', '0ffs3t_Y'))

        self.xfov = self.settings.get_int('Xet0riA1M', 'xFov')
        self.yfov = self.settings.get_int('Xet0riA1M', 'yFov')

    def get_colors(self):
        lower_color = np.array(self.settings.get_float_list('C0l0rs', 'L0w3rC0lor'))
        upper_color = np.array(self.settings.get_float_list('C0l0rs', 'Upp3rC0lor'))
        return lower_color, upper_color

    def listen(self):
        while True:
            if win32api.GetAsyncKeyState(self.RELOAD_KEY) < 0:
                self.reload_settings()
            if win32api.GetAsyncKeyState(self.PAUSE_RESUME_KEY) < 0:  # Verificar si se presiona la tecla de pausa/reanudar
                self.paused = not self.paused  # Alternar estado de pausa
                print(colored("Program paused", 'red')) if self.paused else print(colored("Program resumed", 'green'))
                time.sleep(0.5)  # Agregar un pequeño retraso
            if win32api.GetAsyncKeyState(self.Xet0riA1M_KEY) < 0:
                self.process()

    def reload_settings(self):
        os.system('cls')
        print(colored('''
                                     __  __  _____   _____    ___    ____    ___ 
                                     \ \/ / | ____| |_   _|  / _ \  |  _ \  |_ _|
                                      \  /  | _|      | |   | | | | | |_) |  | | 
                                      /  \  | |___    | |   | |_| | |  _ <   | | 
                                     /_/\_\ |_____|   |_|    \___/  |_| \_\ |___|
                                             
                                              v1.0  Und3t3ct3d''', 'green'))  
        self.settings = Settings() 
        self.LOWER_COLOR, self.UPPER_COLOR = self.get_colors()  
        self.configure()  
        print(colored("Config loaded", 'green'))
        self.print_settings()
        self.grabber = Capture(self.grabber.x, self.grabber.y, self.xfov, self.yfov)

    def print_settings(self):
        with open('settings.ini', 'r') as f:
            print(f.read())

    def process(self):
        if self.paused:  # Si está en pausa, regresar inmediatamente
            return

        hsv = cv2.cvtColor(self.grabber.get_screen(), cv2.COLOR_BGR2HSV)
        mask = cv2.inRange(hsv, self.LOWER_COLOR, self.UPPER_COLOR)
        kernel = np.ones((3, 3), np.uint8)
        dilated = cv2.dilate(mask, kernel, iterations=5)
        thresh = cv2.threshold(dilated, 60, 255, cv2.THRESH_BINARY)[1]
        contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_NONE)

        if contours:
            screen_center = (self.grabber.xfov // 2, self.grabber.yfov // 2)
            min_distance = float('inf')
            closest_contour = None

            for contour in contours:
                x, y, w, h = cv2.boundingRect(contour)
                center = (x + w // 2, y + h // 2)
                distance = ((center[0] - screen_center[0]) ** 2 + (center[1] - screen_center[1]) ** 2) ** 0.5

                if distance < min_distance:
                    min_distance = distance
                    closest_contour = contour

            if closest_contour is not None:
                x, y, w, h = cv2.boundingRect(closest_contour)
                center = (x + w // 2, y + h // 2)
                cX = center[0]
                cY = y + int(h * 0.2)  
                x_diff = cX - self.grabber.xfov // 2
                y_diff = cY - self.grabber.yfov // 2
                x_diff -= int(self.xspeed() * self.OFFSET_X)
                y_diff -= int(self.yspeed() * self.OFFSET_Y)
                x_diff_int = int(self.xspeed() * x_diff)
                y_diff_int = int(self.yspeed() * y_diff)
                self.mouse.move_mouse(x_diff_int, y_diff_int)
