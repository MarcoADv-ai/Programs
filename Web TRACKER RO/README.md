# 🎯 Ravenmoor MVP Tracker

Una aplicación web moderna para rastrear y detectar nuevos MVPs derrotados en el servidor de Ragnarok Online - Ravenmoor.

## 🌐 HOSTING SIMPLE (Solo subir archivos)

**¿Quieres hostear en tu servidor web? Lee:** [`INSTALACION_RAPIDA.txt`](INSTALACION_RAPIDA.txt)

### Archivos necesarios para hosting PHP:
- `index_php.html` → renombrar a `index.html`
- `proxy.php`
- `.htaccess`
- `mvps_data.json` (con permisos 666/777)

**Sube estos archivos a tu hosting y ¡listo!** 🚀

---

## 🌟 Características

- ✅ **Detección automática** de nuevos MVPs cada 5 segundos
- ⏰ **Timestamp preciso** de la hora exacta de detección
- 🚫 **Sin duplicados** - Solo muestra MVPs nuevos
- 🔄 **Actualización automática** sin necesidad de recargar la página
- 🎨 **Interfaz moderna y responsive** con diseño gradiente
- 📊 **Estadísticas en tiempo real** de MVPs detectados

## 📋 Requisitos Previos

- Python 3.7 o superior
- pip (gestor de paquetes de Python)

## 🚀 Instalación

1. **Clonar o descargar este proyecto**

2. **Instalar las dependencias de Python:**

```bash
pip install -r requirements.txt
```

## 💻 Uso

1. **Iniciar el servidor backend:**

```bash
python app.py
```

Deberías ver el mensaje:
```
🚀 Servidor iniciado en http://localhost:5000
📊 Rastreando MVPs de Ravenmoor...
```

2. **Abrir la aplicación en tu navegador:**

Abre tu navegador web y ve a:
```
http://localhost:5000
```

3. **¡Listo!** La aplicación comenzará a rastrear automáticamente los MVPs cada 5 segundos.

## 🎮 Cómo Funciona

1. **Backend (Flask)**: 
   - Se conecta a la página de ranking de Ravenmoor
   - Extrae los datos de la tabla de MVPs usando web scraping
   - Compara con MVPs anteriores para detectar nuevos
   - Asigna timestamp automáticamente a cada nuevo MVP

2. **Frontend (HTML/CSS/JavaScript)**:
   - Se actualiza automáticamente cada 5 segundos
   - Muestra solo los MVPs nuevos detectados
   - Interfaz responsive que funciona en desktop y móvil
   - Animaciones suaves y diseño moderno

## 📊 Endpoints de API

- `GET /` - Página principal de la aplicación
- `GET /api/mvps` - Obtiene nuevos MVPs detectados
- `GET /api/all` - Obtiene todos los MVPs actuales (debug)
- `GET /api/reset` - Reinicia el tracking de MVPs

## 🔧 Características Técnicas

- **Backend**: Flask + BeautifulSoup4 para web scraping
- **Frontend**: HTML5 + CSS3 + JavaScript vanilla
- **CORS**: Habilitado para desarrollo local
- **Almacenamiento**: En memoria (se reinicia con el servidor)

## 📱 Interfaz de Usuario

La interfaz muestra para cada MVP:

- 🎯 **Nombre del MVP** derrotado
- 👤 **Personaje** que lo derrotó
- 💰 **Experiencia** obtenida
- 🗺️ **Mapa** donde fue derrotado
- ⏰ **Hora exacta** de detección

## 🔄 Botón de Reinicio

Puedes reiniciar el tracking en cualquier momento usando el botón "🔄 Reiniciar". Esto:
- Borra todos los MVPs detectados anteriormente
- Reinicia el contador
- Permite volver a detectar MVPs desde cero

## ⚠️ Notas Importantes

- La aplicación debe estar ejecutándose para detectar nuevos MVPs
- Los datos se almacenan en memoria, se pierden al cerrar el servidor
- Requiere conexión a Internet para acceder a Ravenmoor
- El intervalo de actualización es de 5 segundos (configurable en `index.html`)

## 🛠️ Personalización

### Cambiar el intervalo de actualización:

Edita `index.html` línea 387:
```javascript
updateInterval = setInterval(fetchNewMVPs, 5000); // 5000ms = 5 segundos
```

### Cambiar el puerto del servidor:

Edita `app.py` última línea:
```python
app.run(debug=True, host='0.0.0.0', port=5000)
```

## 📝 Estructura del Proyecto

```
web tracker/
├── app.py              # Servidor backend Flask
├── index.html          # Interfaz de usuario
├── requirements.txt    # Dependencias Python
└── README.md          # Este archivo
```

## 🐛 Solución de Problemas

**El servidor no inicia:**
- Verifica que Python esté instalado: `python --version`
- Instala las dependencias: `pip install -r requirements.txt`

**No se detectan MVPs:**
- Verifica tu conexión a Internet
- Comprueba que Ravenmoor esté accesible: https://www.ravenmoor-ro.com/?module=ranking&action=mvp

**Error de CORS:**
- Asegúrate de acceder vía `http://localhost:5000` y no abriendo el HTML directamente

## 📄 Licencia

Este proyecto es de código abierto y está disponible para uso personal y educativo.

---

**¡Disfruta rastreando MVPs en Ravenmoor! 🎮✨**

