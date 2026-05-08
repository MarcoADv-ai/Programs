import imgui
from imgui.integrations.glfw import GlfwRenderer
import glfw
import tkinter as tk
import json
import logging
import mss
import pytesseract
from PIL import Image
import pyautogui
import time

# Configuración básica de logging
logging.basicConfig(level=logging.DEBUG, format='%(asctime)s - %(levelname)s - %(message)s')

# Configuración de pytesseract
pytesseract.pytesseract.tesseract_cmd = r'C:\Program Files\Tesseract-OCR\tesseract.exe'

# Leer configuración inicial de settings.in
def read_settings():
    try:
        with open("settings.in", "r") as file:
            return json.load(file)
    except FileNotFoundError:
        logging.warning("settings.in not found. Defaulting to manual input.")
        return None
    except json.JSONDecodeError:
        logging.error("Invalid JSON in settings.in.")
        return None

def save_settings(settings):
    with open("settings.in", "w") as file:
        json.dump(settings, file)

def impl_glfw_init():
    if not glfw.init():
        logging.error("Could not initialize OpenGL context")
        exit(1)

    glfw.window_hint(glfw.CONTEXT_VERSION_MAJOR, 4)
    glfw.window_hint(glfw.CONTEXT_VERSION_MINOR, 1)
    glfw.window_hint(glfw.OPENGL_PROFILE, glfw.OPENGL_CORE_PROFILE)
    glfw.window_hint(glfw.OPENGL_FORWARD_COMPAT, glfw.TRUE)

    window = glfw.create_window(640, 480, "CAPTCHA Selector", None, None)
    glfw.make_context_current(window)

    if not window:
        glfw.terminate()
        logging.error("Could not initialize Window")
        exit(1)

    return window

def draw_selection_area():
    root = tk.Tk()
    root.attributes('-fullscreen', True)
    root.attributes('-alpha', 0.3)  # Semi-transparent
    root.attributes('-topmost', True)  # Always on top

    canvas = tk.Canvas(root, cursor='cross')
    canvas.pack(fill=tk.BOTH, expand=True)
    start_x, start_y = None, None

    def on_click(event):
        nonlocal start_x, start_y
        start_x, start_y = event.x, event.y

    def on_drag(event):
        nonlocal start_x, start_y
        end_x, end_y = event.x, event.y
        canvas.delete('selection')
        canvas.create_rectangle(start_x, start_y, end_x, end_y, outline='red', tag='selection')

    def close_overlay(event):
        nonlocal root, start_x, start_y
        end_x, end_y = event.x, event.y
        root.destroy()
        monitor = {"top": min(start_y, end_y), "left": min(start_x, end_x), "width": abs(end_x - start_x), "height": abs(end_y - start_y)}
        logging.info(f"Selected coordinates: {monitor}")
        return monitor

    root.bind('<Button-1>', on_click)
    root.bind('<B1-Motion>', on_drag)
    root.bind('<ButtonRelease-1>', close_overlay)

    root.mainloop()

def search_area_for_text(target_text, area):
    with mss.mss() as sct:
        while True:
            sct_img = sct.grab(area)
            img = Image.frombytes('RGB', (sct_img.width, sct_img.height), sct_img.rgb)
            img.save("debug_area.png")  # Guardar imagen para debugging
            logging.debug("Area screenshot saved for debugging.")

            # Configuración de Tesseract para usar PSM 6
            config = '--psm 6'
            text = pytesseract.image_to_string(img, config=config).strip()

            logging.debug(f"Detected text in area: {text}")
            if target_text in text:
                logging.info(f"Target text '{target_text}' found in area.")
                return True

            time.sleep(7)  # Esperar 7 segundos antes de la próxima captura

def monitor_captcha(monitor):
    with mss.mss() as sct:
        while True:
            sct_img = sct.grab(monitor)
            img = Image.frombytes('RGB', (sct_img.width, sct_img.height), sct_img.rgb)
            img.save("debug_screenshot.png")  # Guardar imagen para debugging
            logging.debug("Screenshot saved for debugging.")

            # Configuración de Tesseract para usar PSM 6
            config = '--psm 6'
            text = pytesseract.image_to_string(img, config=config).strip()

            if text:
                logging.info(f"Detected text: {text}")
                try:
                    numeric_text = ''.join(filter(str.isdigit, text))  # Filtrar solo números del texto
                    if numeric_text:
                        logging.info(f"Numeric CAPTCHA Detected: {numeric_text}")
                        time.sleep(3)  # Esperar 3 segundos antes de escribir
                        pyautogui.write(numeric_text)  # Escribir el código
                        pyautogui.press('enter')  # Presionar Enter
                        time.sleep(3)
                        pyautogui.press('enter')  # Presionar Enter

                        break
                except Exception as e:
                    logging.error(f"Error writing CAPTCHA: {e}")
            else:
                logging.info("No text detected.")

            time.sleep(1)  # Escanear cada segundo

def main():
    settings = read_settings()
    window = impl_glfw_init()
    imgui.create_context()
    renderer = GlfwRenderer(window)

    manual_coords = {"top": 0, "left": 0, "width": 100, "height": 100}
    area_coords = {"top": 0, "left": 0, "width": 100, "height": 100}
    if settings:
        manual_coords.update(settings.get("captcha_coords", {}))
        area_coords.update(settings.get("area_coords", {}))

    while not glfw.window_should_close(window):
        glfw.poll_events()
        renderer.process_inputs()
        imgui.new_frame()

        if imgui.begin("Auto Captcha"):
            if imgui.button("Select Area for Validation"):
                logging.debug("Start Selection for validation button pressed")
                selected_monitor = draw_selection_area()
                if selected_monitor:
                    area_coords.update(selected_monitor)

            if imgui.button("Select CAPTCHA Area"):
                logging.debug("Start Selection button pressed")
                selected_monitor = draw_selection_area()
                if selected_monitor:
                    manual_coords.update(selected_monitor)

            # Actualizar automáticamente los campos del GUI después de seleccionar
            imgui.text("Validation Area Coordinates:")
            changed_top, area_coords["top"] = imgui.input_int("Top (Validation)", area_coords["top"])
            changed_left, area_coords["left"] = imgui.input_int("Left (Validation)", area_coords["left"])
            changed_width, area_coords["width"] = imgui.input_int("Width (Validation)", area_coords["width"])
            changed_height, area_coords["height"] = imgui.input_int("Height (Validation)", area_coords["height"])

            imgui.text("CAPTCHA Area Coordinates:")
            changed_top, manual_coords["top"] = imgui.input_int("Top (CAPTCHA)", manual_coords["top"])
            changed_left, manual_coords["left"] = imgui.input_int("Left (CAPTCHA)", manual_coords["left"])
            changed_width, manual_coords["width"] = imgui.input_int("Width (CAPTCHA)", manual_coords["width"])
            changed_height, manual_coords["height"] = imgui.input_int("Height (CAPTCHA)", manual_coords["height"])

            if imgui.button("Start CAPTCHA Bypass for Honey RO"):
                logging.debug("Start Monitoring button pressed")
                save_settings({"captcha_coords": manual_coords, "area_coords": area_coords})
                if search_area_for_text("Hello, illegal software", area_coords):
                    monitor_captcha(manual_coords)

        imgui.end()
        imgui.render()
        renderer.render(imgui.get_draw_data())
        glfw.swap_buffers(window)

    renderer.shutdown()
    glfw.terminate()

if __name__ == "__main__":
    main()
