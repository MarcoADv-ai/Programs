# Bratva MVP Tracker V2.0

Tracker web para controlar los tiempos de aparición de los MVP en Sakura RO.

La versión publicada se puede consultar aquí: [http://161.153.198.115/testeo1/](http://161.153.198.115/testeo1/)

## ¿Por qué hice este proyecto?

El proyecto nació porque dentro del juego llevábamos los tiempos de los MVP manualmente. Después de varias muertes era fácil perder el orden, calcular mal el respawn o no saber cuál aparecería primero.

La primera versión solamente mostraba timers. Con el tiempo fui agregando el historial, las prioridades de la guild, los resets individuales y el cálculo especial de Convex Mirror. Más adelante reemplacé los archivos JSON por SQLite y añadí un catálogo administrativo para no tener que modificar el código cada vez que quisiera registrar o corregir un MVP.

Esta variante utiliza Bright Data para consultar el ranking público de Sakura RO. Cuando encuentra una muerte nueva, el backend revisa el nombre y el mapa, guarda el evento y genera el timer correspondiente.

Para los lineamientos escolares, el proyecto entra en el dominio de **Catálogo de contenido**.

## Entidad principal

La entidad principal es el **MVP**. Cada MVP tiene un nombre, un tiempo mínimo y máximo de respawn, uno o varios mapas y, de forma opcional, un grupo compartido y un GIF personalizado.

Separé los mapas en otra tabla porque un mismo MVP puede aparecer en más de un lugar y cada mapa puede necesitar tiempos distintos.

Las tablas principales son:

| Tabla | Información que guarda |
|---|---|
| `mvp_definitions` | Datos generales de cada MVP y su GIF. |
| `mvp_maps` | Mapas asociados y tiempos especiales de respawn. |
| `kill_events` | Muertes detectadas en el ranking. |
| `last_check_events` | Última consulta procesada para evitar duplicados. |
| `active_timers` | Timers que se encuentran activos. |

La relación más importante es:

```text
mvp_definitions (1) -------- (N) mvp_maps
```

## Funcionalidades y CRUD

El tracker se puede consultar sin iniciar sesión. Cualquier visitante puede ver timers, historial, prioridades y utilizar los controles públicos. El inicio de sesión solamente habilita la administración del catálogo.

### Crear

Desde **Catálogo de MVP** puedo registrar un nuevo MVP indicando su nombre, respawn, mapas y un GIF opcional. El registro aparece en el catálogo aunque todavía no exista un timer para él.

### Consultar

El catálogo muestra todos los MVP guardados en SQLite. Se puede buscar por nombre o mapa. En la vista pública se muestran los timers activos, el historial de muertes y la lista de prioridad ordenada por próxima aparición.

### Editar

Puedo cambiar el nombre, los tiempos, el grupo compartido, el GIF o los mapas asociados. Esto me permitió corregir casos como `Memory of Thanatos`, `Nidhoggr's Shadow` y mapas cuyos nombres no coincidían exactamente con el ranking.

### Eliminar

La eliminación pide confirmación antes de borrar. Al eliminar un MVP también se quitan sus mapas y timers activos, pero se conserva el texto de las muertes antiguas dentro del historial.

Además del CRUD, el proyecto incluye:

- Generación automática de timers desde el ranking.
- Reset general y reset individual por MVP.
- Prioridades de la guild ordenadas por tiempo.
- Delay exacto de Convex Mirror para Wounded Morroc y Valkyrie Randgris.
- GIF personalizados guardados dentro de SQLite.
- Sesión administrativa y protección CSRF.

## Reglas de negocio

Estas son las validaciones principales que apliqué para evitar datos incorrectos:

1. No se pueden registrar dos MVP con el mismo nombre.
2. Cada MVP debe tener por lo menos un mapa y no puede repetirlo.
3. Los tiempos no pueden ser negativos y el máximo debe ser igual o mayor al mínimo.
4. Un timer solo se genera cuando el nombre y el mapa coinciden con el catálogo.
5. Solamente el administrador puede crear, editar o eliminar registros.
6. Los GIF deben ser archivos válidos y no superar 5 MB.

Reiniciar un timer no elimina el historial. Esto es intencional porque el reset sirve para descartar una cuenta regresiva, no para borrar el registro de la muerte.

## Tecnologías utilizadas

- **Frontend:** React, JavaScript, Vite, CSS y Lucide React.
- **Backend:** Python, Flask y Gunicorn.
- **Base de datos:** SQLite.
- **Consulta del ranking:** Bright Data y `urllib3`.
- **Servidor:** Ubuntu, Nginx y systemd.
- **Control de versiones:** Git y GitHub.

Elegí SQLite porque el tracker funciona en un solo VPS y no necesita administrar otro servidor de base de datos. Si en el futuro hubiera varias instancias escribiendo al mismo tiempo, consideraría migrarlo a PostgreSQL.

## Cómo ejecutarlo localmente

### Requisitos

- Python 3.10 o superior.
- Token y Collector ID de Bright Data.
- Node.js y pnpm solo si se quiere modificar el frontend.

### Windows

Abre PowerShell dentro de la carpeta del proyecto:

```powershell
py -3 -m venv venv
.\venv\Scripts\Activate.ps1
python -m pip install -r requirements.txt
```

Configura los datos necesarios para esa sesión:

```powershell
$env:BRIGHTDATA_API_TOKEN="TU_TOKEN"
$env:BRIGHTDATA_COLLECTOR_ID="TU_COLLECTOR_ID"
$env:FLASK_SECRET_KEY="UNA_CLAVE_ALEATORIA_LARGA"
$env:ADMIN_USERNAME="admin"
$env:ADMIN_PASSWORD="TU_CONTRASENA"
python app.py
```

Después abre [http://127.0.0.1:5000/testeo1/](http://127.0.0.1:5000/testeo1/).

### Linux

```bash
python3 -m venv venv
./venv/bin/pip install -r requirements.txt

export BRIGHTDATA_API_TOKEN="TU_TOKEN"
export BRIGHTDATA_COLLECTOR_ID="TU_COLLECTOR_ID"
export FLASK_SECRET_KEY="UNA_CLAVE_ALEATORIA_LARGA"
export ADMIN_USERNAME="admin"
export ADMIN_PASSWORD="TU_CONTRASENA"

./venv/bin/python app.py
```

Las contraseñas y el token no se guardan en el repositorio. `.env.example` sirve únicamente como referencia.

## Base de datos

No es necesario instalar SQLite por separado. En el primer inicio, el backend crea `tracker.db`, ejecuta `schema.sql` y carga el catálogo inicial desde `seed.sql`.

Para revisar la estructura durante la exposición se puede abrir `tracker.db` con DB Browser for SQLite. Por ejemplo, esta consulta muestra la relación entre MVP y mapas:

```sql
SELECT
    m.name AS mvp,
    mp.map_name AS mapa,
    mp.respawn_min_minutes AS minimo,
    mp.respawn_max_minutes AS maximo
FROM mvp_definitions AS m
LEFT JOIN mvp_maps AS mp ON mp.mvp_id = m.id
ORDER BY m.name, mp.position;
```

La base real, los respaldos y los archivos de configuración sensibles están excluidos mediante `.gitignore`.

## Estructura del proyecto

```text
testeo1_brightdata/
|-- app.py                 Backend Flask, API y monitor
|-- database.py            Consultas y operaciones de SQLite
|-- schema.sql             Tablas, relaciones e índices
|-- seed.sql               Catálogo inicial de MVP
|-- requirements.txt       Dependencias de Python
|-- frontend/              Código fuente de React
|-- dist/                  Frontend compilado
|-- deploy/                Configuración de Nginx y systemd
|-- .env.example           Ejemplo de variables necesarias
`-- README.md              Documentación del proyecto
```

## Autor

**Marco Alvarez**  
Licenciatura en Informática - Universidad Autónoma de Sinaloa.
