# 🚀 Ravenmoor MVP Tracker - LEE ESTO PRIMERO

## ✨ ¿Qué es esto?

Una aplicación web que **detecta automáticamente** nuevos MVPs derrotados en Ravenmoor y muestra la **hora exacta** en que aparecen.

- ✅ Actualización automática cada 5 segundos
- ✅ Sin duplicados
- ✅ Sin necesidad de recargar la página
- ✅ Interfaz moderna y responsive

---

## 🎯 DOS FORMAS DE USAR

### 📱 OPCIÓN 1: USO LOCAL (En tu PC)

**Archivos necesarios:**
- `app.py`
- `index.html`
- `requirements.txt`
- `start.bat`

**Pasos:**
1. Doble clic en `start.bat`
2. Abre: `http://localhost:5000`
3. ¡Listo!

---

### 🌐 OPCIÓN 2: HOSTING WEB (En Internet)

**Archivos necesarios:**
- `index_php.html` → renombrar a `index.html`
- `proxy.php`
- `.htaccess`
- `mvps_data.json`

**Pasos:**
1. Sube los 4 archivos a tu hosting (public_html/)
2. Renombra `index_php.html` a `index.html`
3. Da permisos 666 o 777 a `mvps_data.json`
4. Abre: `https://tudominio.com`
5. ¡Listo!

📖 **Guía detallada:** [`INSTALACION_RAPIDA.txt`](INSTALACION_RAPIDA.txt)

---

## 📦 Estructura de Archivos

```
📂 Proyecto MVP Tracker
│
├── 📱 PARA USO LOCAL (Python/Flask)
│   ├── app.py                    # Servidor backend
│   ├── index.html                # Interfaz web (Flask)
│   ├── requirements.txt          # Dependencias Python
│   └── start.bat                 # Iniciador automático
│
├── 🌐 PARA HOSTING WEB (PHP)
│   ├── index_php.html           # Interfaz web (PHP) - RENOMBRAR a index.html
│   ├── proxy.php                # Backend PHP
│   ├── .htaccess                # Configuración Apache
│   └── mvps_data.json           # Almacenamiento de datos
│
└── 📚 DOCUMENTACIÓN
    ├── README.md                # Documentación completa
    ├── LEEME_PRIMERO.md        # Este archivo
    ├── INSTALACION_RAPIDA.txt  # Guía hosting web
    ├── HOSTING_GUIDE.md        # Guía detallada hosting
    └── ARCHIVOS_PARA_SUBIR.txt # Lista de archivos para hosting
```

---

## 🤔 ¿Cuál opción elegir?

### Usa OPCIÓN 1 (Local) si:
- ✅ Solo quieres usar en tu PC
- ✅ No tienes hosting web
- ✅ Tienes Python instalado

### Usa OPCIÓN 2 (Hosting) si:
- ✅ Quieres acceder desde cualquier dispositivo
- ✅ Tienes un hosting web (Hostinger, SiteGround, etc.)
- ✅ Tu hosting tiene PHP

---

## 📋 Requisitos

### Para Opción 1 (Local):
- Python 3.7+
- Conexión a Internet

### Para Opción 2 (Hosting):
- Hosting con PHP 7.0+
- cURL habilitado
- Permisos de escritura

---

## 🎮 Características

- 🎯 **Detección automática** de nuevos MVPs
- ⏰ **Timestamp preciso** cuando aparece el MVP
- 🔄 **Actualización automática** cada 5 segundos
- 📊 **Estadísticas** de MVPs detectados
- 🎨 **Interfaz moderna** con animaciones
- 📱 **Responsive** - funciona en móvil y PC

---

## 🆘 Ayuda Rápida

### Error al iniciar (Opción 1):
```bash
pip install -r requirements.txt
python app.py
```

### Error en hosting (Opción 2):
- Verifica que `mvps_data.json` tenga permisos 666 o 777
- Asegúrate que PHP y cURL estén habilitados
- Lee: `INSTALACION_RAPIDA.txt`

---

## 📖 Documentación Completa

- **Uso local:** Ver [`README.md`](README.md)
- **Hosting web:** Ver [`INSTALACION_RAPIDA.txt`](INSTALACION_RAPIDA.txt)
- **Guía detallada:** Ver [`HOSTING_GUIDE.md`](HOSTING_GUIDE.md)

---

## ✅ Inicio Rápido

### Local (Python):
```bash
# Opción A: Automático
start.bat

# Opción B: Manual
pip install -r requirements.txt
python app.py
```

### Hosting (PHP):
```bash
1. Sube: index_php.html, proxy.php, .htaccess, mvps_data.json
2. Renombra: index_php.html → index.html
3. Permisos: mvps_data.json → 666/777
4. Abre: https://tudominio.com
```

---

**¡Comienza a trackear MVPs ahora! 🎯✨**

