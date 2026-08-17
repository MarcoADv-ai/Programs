# Bratva MVP Tracker - Testeo 1 con Bright Data

Aplicación web para consultar, administrar y monitorear MVP del servidor Sakura RO. Esta variante obtiene las muertes publicadas en el ranking mediante Bright Data, registra el historial en SQLite y genera cuentas regresivas según el MVP y el mapa detectados.

El proyecto pertenece al dominio **Catálogo de contenido** y cumple un flujo CRUD completo sobre la entidad principal **MVP**.

## 1. Descripción del proyecto

Bratva MVP Tracker evita calcular manualmente los tiempos de reaparición. La aplicación combina:

- Tracker público con timers, prioridades, historial, búsqueda y filtros.
- Catálogo administrativo para gestionar MVP, mapas, respawns y GIF personalizados.
- Monitor automático del ranking público a través de Bright Data.
- Persistencia relacional mediante SQLite.

La instalación de prueba está preparada para ejecutarse bajo la ruta `/testeo1/`.

### Aplicación publicada

- [http://161.153.198.115/testeo1/](http://161.153.198.115/testeo1/)

## 2. Entidad principal

La entidad principal es el **MVP**. Cada registro contiene:

- Identificador único.
- Nombre utilizado por el ranking.
- Respawn mínimo y máximo.
- Grupo de aparición compartida opcional.
- GIF personalizado opcional.
- Uno o varios mapas asociados.

Un mapa puede utilizar el respawn base o definir un intervalo particular.

### Modelo relacional

| Tabla | Propósito |
|---|---|
| `mvp_definitions` | Catálogo de MVP, respawns base y GIF almacenados como BLOB. |
| `mvp_maps` | Mapas relacionados y posibles respawns específicos. |
| `kill_events` | Historial persistente de muertes detectadas. |
| `last_check_events` | Eventos de la última consulta usados para evitar duplicados. |
| `active_timers` | Cuentas regresivas activas. |

Relación principal:

```text
mvp_definitions (1) -------- (N) mvp_maps
```

## 3. Funcionalidades CRUD

### Crear

El administrador puede registrar un MVP con nombre, respawn, grupo opcional, uno o varios mapas y un GIF de hasta 5 MB.

### Consultar

La aplicación permite consultar el catálogo completo, buscar por MVP o mapa, revisar timers activos, prioridades e historial. El tracker es público; el catálogo administrativo requiere iniciar sesión.

### Actualizar

El administrador puede modificar el nombre, respawn, grupo, GIF, mapas asociados y tiempos particulares por mapa. Los cambios persisten en SQLite.

### Eliminar

El administrador puede eliminar un MVP después de confirmar la acción. Se eliminan su configuración, mapas y timers activos relacionados; el historial conserva la información textual de eventos anteriores.

### Funciones adicionales

- Consulta del ranking mediante Bright Data.
- Normalización de nombres como `Memory of Thanatos` y `Nidhoggr's Shadow`.
- Generación automática y ordenamiento de timers.
- Lista de prioridad de la guild.
- Reset general e individual de timers.
- Delay exacto por Convex Mirror para Wounded Morroc y Valkyrie Randgris.
- Sesión administrativa y protección CSRF para el CRUD.

## 4. Reglas de negocio

1. El nombre de cada MVP debe ser único.
2. Cada MVP debe tener al menos un mapa asociado.
3. Un MVP no puede repetir el mismo mapa.
4. El respawn máximo debe ser igual o mayor al mínimo.
5. Los tiempos de respawn no pueden ser negativos.
6. Solo se aceptan archivos GIF válidos de hasta 5 MB.
7. Solo una sesión administrativa puede modificar el catálogo.
8. Solo se genera un timer cuando nombre y mapa coinciden con la configuración.
9. El delay exacto solo admite valores dentro del intervalo permitido.
10. Reiniciar timers no elimina el historial de muertes.

## 5. Tecnologías utilizadas

### Frontend

- React, JavaScript y Vite.
- CSS responsivo.
- Lucide React.

### Backend y datos

- Python 3 y Flask.
- SQLite y SQL relacional.
- `urllib3` para comunicarse con Bright Data.
- Gunicorn en producción.

### Infraestructura

- Nginx como proxy inverso.
- systemd para mantener el servicio activo.
- VPS Ubuntu.
- GitHub para control de versiones.

## 6. Instrucciones de ejecución local

### Requisitos

- Python 3.10 o superior.
- Node.js y pnpm, únicamente para recompilar el frontend.
- Credenciales válidas de Bright Data para actualizar el ranking.

### 6.1 Backend en Windows

Abre PowerShell dentro del proyecto:

```powershell
cd "C:\ruta\al\proyecto\testeo1_brightdata"
py -3 -m venv venv
.\venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
```

Configura las variables de esta sesión. No publiques el token ni las contraseñas:

```powershell
$env:BRIGHTDATA_API_TOKEN="TU_TOKEN"
$env:BRIGHTDATA_COLLECTOR_ID="TU_COLLECTOR_ID"
$env:FLASK_SECRET_KEY="UNA_CLAVE_ALEATORIA_LARGA"
$env:ADMIN_USERNAME="admin"
$env:ADMIN_PASSWORD="TU_CONTRASENA"
python app.py
```

Abre [http://127.0.0.1:5000/testeo1/](http://127.0.0.1:5000/testeo1/).

### 6.2 Backend en Linux

```bash
cd /ruta/al/proyecto/testeo1_brightdata
python3 -m venv venv
./venv/bin/pip install -r requirements.txt
export BRIGHTDATA_API_TOKEN="TU_TOKEN"
export BRIGHTDATA_COLLECTOR_ID="TU_COLLECTOR_ID"
export FLASK_SECRET_KEY="UNA_CLAVE_ALEATORIA_LARGA"
export ADMIN_USERNAME="admin"
export ADMIN_PASSWORD="TU_CONTRASENA"
./venv/bin/python app.py
```

### 6.3 Inicialización de SQLite

No se necesita instalar un servidor de base de datos. Al iniciar la aplicación:

1. Se crea `tracker.db` si no existe.
2. `schema.sql` crea tablas, relaciones, índices y restricciones.
3. `seed.sql` carga el catálogo inicial cuando la base está vacía.

La base y sus archivos auxiliares están excluidos de Git para evitar publicar datos reales.

### 6.4 Recompilar el frontend

`dist/` se conserva para que Flask pueda servir la interfaz inmediatamente. Para regenerarlo:

```bash
cd frontend
corepack enable
pnpm install
pnpm run build
cd ..
```

## 7. Configuración de producción

La carpeta `deploy/` incluye:

- `sakura-testeo1.service`: servicio systemd en `/var/www/sakura-testeo1`.
- `nginx-testeo1.conf`: ruta pública `/testeo1/` y proxy al backend.

En el VPS, las variables sensibles se almacenan fuera del repositorio en:

```text
/etc/sakura-testeo1.env
```

## 8. Estructura del proyecto

```text
testeo1_brightdata/
|-- app.py                 Backend Flask, API y monitor
|-- database.py            Persistencia SQLite y operaciones CRUD
|-- schema.sql             Estructura relacional
|-- seed.sql               Catálogo inicial
|-- requirements.txt       Dependencias Python
|-- frontend/              Código fuente React
|-- dist/                  Frontend compilado listo para Flask
|-- deploy/                Configuración de Nginx y systemd
|-- .env.example           Ejemplo sin secretos
`-- README.md              Documentación principal
```


## Autor

**Marco Alvarez**  
Licenciatura en Informática - Universidad Autónoma de Sinaloa.
