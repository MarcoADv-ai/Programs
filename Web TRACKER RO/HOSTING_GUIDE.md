# 📤 Guía de Hosting - Ravenmoor MVP Tracker

## 🎯 Solución Simple para Tu Host (PHP)

Esta es la solución **MÁS SIMPLE** - solo sube archivos y listo.

### 📋 Requisitos del Hosting

Tu hosting debe tener:
- ✅ PHP 7.0 o superior
- ✅ cURL habilitado
- ✅ Permisos de escritura de archivos

### 📁 Archivos a Subir

Sube estos archivos a la carpeta raíz de tu hosting (public_html o www):

```
📦 Tu Hosting
 ┣ 📄 index_php.html    (Renombrar a index.html)
 ┣ 📄 proxy.php
 ┣ 📄 .htaccess
 ┗ 📄 mvps_data.json    (se creará automáticamente)
```

### 🚀 Pasos de Instalación

#### **Opción 1: Via FTP/FileZilla (Recomendado)**

1. **Descarga FileZilla** (si no lo tienes)
   - https://filezilla-project.org/

2. **Conecta a tu hosting via FTP**
   - Host: ftp.tudominio.com
   - Usuario: tu_usuario_ftp
   - Contraseña: tu_contraseña_ftp

3. **Sube los archivos**
   ```
   - index_php.html  →  RENOMBRAR a index.html
   - proxy.php
   - .htaccess
   ```

4. **Establece permisos** (importante)
   - Clic derecho en la carpeta principal → Permisos
   - Carpeta principal: `755`
   - proxy.php: `644`
   - index.html: `644`
   - .htaccess: `644`

5. **Crea el archivo de datos**
   - En tu hosting, crea un archivo vacío llamado: `mvps_data.json`
   - Permisos: `666` o `777` (para que PHP pueda escribir)

6. **¡Listo!** Abre tu dominio:
   ```
   https://tudominio.com
   ```

#### **Opción 2: Via cPanel**

1. **Accede a cPanel** de tu hosting

2. **Administrador de Archivos**
   - Ve a "Administrador de Archivos"
   - Navega a `public_html` o `www`

3. **Sube archivos**
   - Clic en "Cargar"
   - Selecciona: `proxy.php`, `index_php.html`, `.htaccess`
   - Espera a que suban

4. **Renombrar archivo**
   - Encuentra `index_php.html`
   - Clic derecho → Renombrar
   - Nuevo nombre: `index.html`

5. **Crear archivo de datos**
   - Clic en "Nuevo Archivo"
   - Nombre: `mvps_data.json`
   - Clic derecho → Permisos → `666` o `777`

6. **Verificar permisos**
   - Todos los archivos deben tener permisos de lectura
   - `mvps_data.json` debe tener permisos de escritura

7. **¡Listo!** Abre:
   ```
   https://tudominio.com
   ```

### 🔧 Verificación

#### Test 1: Probar proxy.php
Abre en navegador:
```
https://tudominio.com/proxy.php?action=all
```

Deberías ver JSON con datos de MVPs.

#### Test 2: Probar la página
Abre:
```
https://tudominio.com
```

Deberías ver la interfaz del tracker.

### ⚠️ Problemas Comunes

#### ❌ Error: "500 Internal Server Error"
**Solución:**
- Verifica que tu hosting tenga PHP habilitado
- Revisa los permisos de los archivos
- Contacta a tu hosting para habilitar cURL

#### ❌ No se detectan MVPs nuevos
**Solución:**
- Verifica permisos de `mvps_data.json` (debe ser `666` o `777`)
- Asegúrate que la carpeta tenga permisos de escritura
- Prueba crear el archivo manualmente con contenido: `[]`

#### ❌ Error CORS / Fetch failed
**Solución:**
- Asegúrate de acceder vía `https://tudominio.com` (no abrir el HTML directamente)
- Verifica que `.htaccess` esté subido correctamente

#### ❌ "File not found" o 404
**Solución:**
- Asegúrate que `index_php.html` fue renombrado a `index.html`
- Verifica que `.htaccess` esté en la raíz

### 🔐 Permisos Correctos

```bash
Carpeta raíz:        755
index.html:          644
proxy.php:           644
.htaccess:           644
mvps_data.json:      666 o 777 (lectura/escritura)
```

### 📊 Hosting Recomendados (Probados)

Estos hostings funcionan perfectamente:

- ✅ **Hostinger** - Perfecto, PHP + cURL incluido
- ✅ **SiteGround** - Excelente rendimiento
- ✅ **HostGator** - Configuración simple
- ✅ **Bluehost** - cPanel fácil de usar
- ✅ **000webhost** - GRATIS, funciona bien

### 🎨 Personalización

#### Cambiar intervalo de actualización:
Edita `index.html` línea ~329:
```javascript
updateInterval = setInterval(fetchNewMVPs, 5000); // 5000 = 5 segundos
```

#### Cambiar colores:
Edita el CSS en `index.html`:
```css
background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
```

### 📱 Acceso desde cualquier dispositivo

Una vez desplegado, accede desde:
- 🖥️ PC: `https://tudominio.com`
- 📱 Móvil: `https://tudominio.com`
- 💻 Tablet: `https://tudominio.com`

### 🔄 Actualización de Datos

Los datos se almacenan en `mvps_data.json`. Para reiniciar:

**Opción 1:** Usar el botón "🔄 Reiniciar" en la web

**Opción 2:** Borrar manualmente
- Via FTP: Elimina `mvps_data.json`
- Via cPanel: Elimina el archivo desde el administrador

### 📝 Resumen Rápido

```bash
1. Sube archivos por FTP/cPanel:
   - index_php.html (renombrar a index.html)
   - proxy.php
   - .htaccess

2. Crea mvps_data.json con permisos 666/777

3. Abre https://tudominio.com

4. ¡Funciona! 🎉
```

---

## 🐍 Alternativa con Python (Si tu host soporta Python)

Si tu hosting soporta Python (como PythonAnywhere, Railway, etc.):

### Archivos necesarios:
```
- app.py
- requirements.txt
- index.html
- Procfile (opcional)
```

### Comandos:
```bash
pip install -r requirements.txt
python app.py
```

---

## 🌐 Dominios Sugeridos

Si aún no tienes dominio:
- `mvptracker-ravenmoor.com`
- `ravenmoor-mvps.com`
- `tu-nombre-mvptracker.com`

---

## ✅ Checklist Final

- [ ] Archivos subidos al hosting
- [ ] index_php.html renombrado a index.html
- [ ] mvps_data.json creado con permisos 666/777
- [ ] Probado: https://tudominio.com/proxy.php?action=all
- [ ] Página principal abre correctamente
- [ ] MVPs se detectan automáticamente

---

## 🆘 Soporte

Si tienes problemas:
1. Verifica los permisos de archivos
2. Asegúrate que PHP y cURL estén habilitados
3. Revisa los logs de error de tu hosting
4. Contacta al soporte de tu hosting

---

**¡Disfruta de tu MVP Tracker desplegado! 🎮✨**

