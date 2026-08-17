import { useEffect, useMemo, useState } from 'react'
import {
  AlertTriangle, CheckCircle2, ChevronDown, Clock3, Crown, History, MapPin,
  Edit3, ImageUp, Info, LogIn, LogOut, Plus, RotateCcw, Save, Search,
  ShieldCheck, Skull, Sparkles, Swords, Trash2, UserRound, X,
} from 'lucide-react'

const BASE_PATH = '/testeo1'
const appPath = (path) => path.startsWith('/') ? `${BASE_PATH}${path}` : path
const apiFetch = (path, options) => fetch(appPath(path), options)

const SPRITES = {
  'Amon Ra': '/mvps/amon_ra.gif',
  'Assassin Cross Eremes': '/mvps/assassin_cross_eremes.gif',
  Atroce: '/mvps/atroce.gif',
  Bacsojin: '/mvps/white_lady.gif',
  Baphomet: '/mvps/baphomet.gif',
  Beelzebub: '/mvps/beelzebub.gif',
  Boitata: '/mvps/boitata.gif',
  'Dark Lord': '/mvps/dark_lord.gif',
  Detardeurus: '/mvps/detale.gif',
  Dracula: '/mvps/dracula.gif',
  Drake: '/mvps/drake.gif',
  Doppelganger: '/mvps/doppelganger.gif',
  Eddga: '/mvps/eddga.gif',
  'Egnigem Cenia': '/mvps/egnigem_cenia.gif',
  'Evil Snake Lord': '/mvps/evil_snake_lord.gif',
  'Fallen Bishop Hibram': '/mvps/fallen_bishop_hibram.gif',
  'Gloom Under Night': '/mvps/gloom_under_night.gif',
  'Golden Thief Bug': '/mvps/golden_thief_bug.gif',
  Gopinich: '/mvps/gopinich.gif',
  Hatii: '/mvps/hatii.gif',
  'High Priest Margaretha': '/mvps/high_priest_margaretha.gif',
  'High Wizard Kathryne': '/mvps/high_wizard_kathryne.gif',
  Ifrit: '/mvps/ifrit.gif',
  'Incantation Samurai': '/mvps/incantation_samurai.gif',
  'Kiel D-01': '/mvps/kiel-d-01.gif',
  'Lady Tanee': '/mvps/lady_tanee.gif',
  'Lord of the Dead': '/mvps/lord_of_death.gif',
  'Lord Knight Seyren': '/mvps/lord_knight_seyren.gif',
  Maya: '/mvps/maya.gif',
  Mistress: '/mvps/mistress.gif',
  'Moonlight Flower': '/mvps/moonlight_flower.gif',
  "Nidhoggr's Shadow": '/mvps/nidhoggr_shadow.gif',
  'Orc Hero': '/mvps/orc_hero.gif',
  'Orc Lord': '/mvps/orc_lord.gif',
  Osiris: '/mvps/osiris.gif',
  Pharaoh: '/mvps/pharaoh.gif',
  Phreeoni: '/mvps/phreeoni.gif',
  'RSX-0806': '/mvps/rsx-0806.gif',
  'Samurai Specter': '/mvps/incantation_samurai.gif',
  'Sniper Cecil': '/mvps/sniper_cecil.gif',
  'Stormy Knight': '/mvps/stormy_knight.gif',
  'Tao Gunka': '/mvps/tao_gunka.gif',
  Thanatos: '/mvps/thanatos_phantom.gif',
  'Turtle General': '/mvps/turtle_general.gif',
  'Valkyrie Randgris': '/mvps/valkyrie_randgris.gif',
  Vesper: '/mvps/vesper.gif',
  'White Lady': '/mvps/white_lady.gif',
  'Whitesmith Howard': '/mvps/whitesmith_howard.gif',
  'Wounded Morroc': '/mvps/wounded_morroc.gif',
}

const PRIORITY = (timer) =>
  timer.map === 'lhz_dun03' ||
  timer.map === 'odin_tem03' ||
  timer.mvp === 'Beelzebub' ||
  (timer.mvp === 'Thanatos' && timer.map === 'thana_t12') ||
  (timer.mvp === "Nidhoggr's Shadow" && timer.map === '2@nyd') ||
  (timer.mvp === 'Wounded Morroc' && timer.map === 'moc_fild22')

const EXACT_DELAY_MVPS = new Set(['Wounded Morroc', 'Valkyrie Randgris'])

const formatTime = (seconds) => {
  if (seconds <= 0) return '00:00:00'
  const hours = Math.floor(seconds / 3600)
  const minutes = Math.floor((seconds % 3600) / 60)
  const secs = seconds % 60
  return [hours, minutes, secs].map((value) => String(value).padStart(2, '0')).join(':')
}

const getState = (timer) => {
  if (timer.is_alive) return 'alive'
  if (timer.in_delay_window) return 'window'
  return 'waiting'
}

const sortByNextSpawn = (a, b) => {
  const order = { alive: 0, window: 1, waiting: 2 }
  const stateA = getState(a)
  const stateB = getState(b)
  const stateDifference = order[stateA] - order[stateB]
  if (stateDifference) return stateDifference
  if (stateA === 'window') return a.seconds_until_max - b.seconds_until_max
  if (stateA === 'waiting') return a.seconds_until_delay - b.seconds_until_delay
  return String(a.delay_window_end).localeCompare(String(b.delay_window_end))
}

function useTrackerData() {
  const [timers, setTimers] = useState([])
  const [history, setHistory] = useState([])
  const [connected, setConnected] = useState(false)
  const [lastUpdate, setLastUpdate] = useState(null)

  useEffect(() => {
    let active = true
    const load = async () => {
      try {
        const [timerResponse, historyResponse] = await Promise.all([
          apiFetch(`/api/timers?t=${Date.now()}`, { cache: 'no-store' }),
          apiFetch(`/api/history?t=${Date.now()}`, { cache: 'no-store' }),
        ])
        if (!timerResponse.ok || !historyResponse.ok) throw new Error('API unavailable')
        const timerData = await timerResponse.json()
        const historyData = await historyResponse.json()
        if (!active) return
        setTimers(timerData.timers || [])
        setHistory([...(historyData.history || [])].reverse())
        setConnected(true)
        setLastUpdate(new Date())
      } catch {
        if (active) setConnected(false)
      }
    }
    load()
    const sync = setInterval(load, 2000)
    return () => { active = false; clearInterval(sync) }
  }, [])

  useEffect(() => {
    const tick = setInterval(() => {
      setTimers((current) => current.map((timer) => {
        if (timer.is_alive) return timer
        const secondsUntilDelay = Math.max(0, timer.seconds_until_delay - 1)
        const secondsUntilMax = Math.max(0, timer.seconds_until_max - 1)
        return {
          ...timer,
          seconds_until_delay: secondsUntilDelay,
          seconds_until_max: secondsUntilMax,
          in_delay_window: secondsUntilDelay === 0 && secondsUntilMax > 0,
          is_alive: secondsUntilMax === 0,
        }
      }))
    }, 1000)
    return () => clearInterval(tick)
  }, [])

  return { timers, history, connected, lastUpdate }
}

function useCatalog() {
  const [catalog, setCatalog] = useState([])
  const loadCatalog = async () => {
    const response = await apiFetch(`/api/catalog?t=${Date.now()}`, { cache: 'no-store' })
    if (!response.ok) throw new Error('No se pudo cargar el catálogo')
    const data = await response.json()
    setCatalog(data.mvps || [])
  }
  useEffect(() => { loadCatalog().catch(() => {}) }, [])
  return { catalog, loadCatalog }
}

function Sprite({ name, customSource }) {
  const source = customSource || SPRITES[name]
  if (!source) {
    return <div className="sprite-fallback"><Skull size={42} /><span>MVP</span></div>
  }
  return <img className="mvp-sprite" src={appPath(source)} alt={`Sprite de ${name}`} />
}

function TimerCard({ timer, priority = false, catalogItem, canEdit, onEdit, onReset }) {
  const supportsExactDelay = EXACT_DELAY_MVPS.has(timer.mvp)
  const [exactMinutes, setExactMinutes] = useState(timer.exact_delay_minutes ?? '')
  const [savingExact, setSavingExact] = useState(false)
  const [exactMessage, setExactMessage] = useState('')

  useEffect(() => {
    if (timer.exact_delay_minutes !== undefined) {
      setExactMinutes(timer.exact_delay_minutes)
    }
  }, [timer.exact_delay_minutes])

  const state = getState(timer)
  const remaining = state === 'alive'
    ? 'ALIVE'
    : formatTime(state === 'window' ? timer.seconds_until_max : timer.seconds_until_delay)
  const elapsed = Math.max(0, timer.total_wait_seconds - timer.seconds_until_delay)
  const progress = state === 'alive'
    ? 100
    : Math.min(100, Math.max(0, (elapsed / timer.total_wait_seconds) * 100))
  const label = state === 'alive' ? 'Disponible' : timer.exact_spawn_at ? 'Hora exacta' : state === 'window' ? 'Delay' : 'En respawn'
  const baseHours = Math.floor(timer.respawn_min_minutes / 60)
  const baseMinutes = timer.respawn_min_minutes % 60
  const baseRespawnLabel = [
    baseHours ? `${baseHours} hora${baseHours === 1 ? '' : 's'}` : '',
    baseMinutes ? `${baseMinutes} min` : '',
  ].filter(Boolean).join(' ')

  const saveExactDelay = async (event) => {
    event.preventDefault()
    setSavingExact(true)
    setExactMessage('')
    try {
      const response = await apiFetch('/api/timers/exact-delay', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: timer.id, minutes: Number(exactMinutes) }),
      })
      const data = await response.json()
      if (!response.ok) throw new Error(data.error || 'No se pudo guardar')
      setExactMessage('Hora exacta guardada')
    } catch (error) {
      setExactMessage(error.message)
    } finally {
      setSavingExact(false)
    }
  }

  return (
    <article className={`timer-card state-${state} ${priority ? 'is-priority' : ''}`}>
      <div className="card-glow" />
      {priority && <div className="priority-ribbon"><Crown size={13} /> Prioridad</div>}
      <div className="sprite-stage"><Sprite name={timer.mvp} customSource={catalogItem?.sprite_url} /></div>
      <div className="timer-content">
        <div className="timer-heading">
          <div>
            <span className={`state-pill ${state}`}>{label}</span>
            <h3>{timer.mvp}</h3>
          </div>
          <strong className="countdown">{remaining}</strong>
        </div>
        <div className="progress-track"><span style={{ width: `${progress}%` }} /></div>
        <div className="timer-meta">
          <span><MapPin size={15} /> {timer.map}</span>
          <span><UserRound size={15} /> {timer.character}</span>
        </div>
        <div className="time-grid">
          <div><small>Muerte</small><b>{timer.killed_at}</b></div>
          <div><small>{timer.exact_spawn_at ? 'Aparición exacta' : 'Delay'}</small><b>{timer.exact_spawn_at || timer.delay_window_start}</b></div>
        </div>
        <div className="card-management">
          <span className="respawn-note"><Clock3 size={14} /> Respawn {timer.respawn_min_minutes}–{timer.respawn_max_minutes} min</span>
          <div>
            {canEdit && <button type="button" onClick={() => onEdit(catalogItem)} disabled={!catalogItem}><Edit3 size={13} /> Editar</button>}
            <button type="button" className="danger" onClick={() => onReset(timer)}><RotateCcw size={13} /> Reset</button>
          </div>
        </div>
        {supportsExactDelay && <form className="exact-delay-form" onSubmit={saveExactDelay}>
          <label htmlFor={`exact-delay-${priority ? 'priority' : 'general'}`}>
            Delay indicado por Convex
            <span>{baseRespawnLabel} + minutos</span>
          </label>
          <div>
            <input
              id={`exact-delay-${priority ? 'priority' : 'general'}`}
              type="number"
              min="0"
              max={timer.respawn_max_minutes - timer.respawn_min_minutes}
              value={exactMinutes}
              onChange={(event) => setExactMinutes(event.target.value)}
              placeholder="Ej. 19"
              required
            />
            <button type="submit" disabled={savingExact}>{savingExact ? 'Guardando…' : 'Guardar'}</button>
          </div>
          {exactMessage && <small className="exact-delay-message">{exactMessage}</small>}
        </form>}
      </div>
    </article>
  )
}

function Section({ icon, eyebrow, title, count, accent, children, openDefault = true }) {
  const [open, setOpen] = useState(openDefault)
  return (
    <section className={`tracker-section accent-${accent}`}>
      <button className="section-heading" onClick={() => setOpen((value) => !value)}>
        <span className="section-icon">{icon}</span>
        <span><small>{eyebrow}</small><strong>{title}</strong></span>
        <em>{count}</em>
        <ChevronDown className={open ? 'rotated' : ''} size={20} />
      </button>
      {open && <div className="section-body">{children}</div>}
    </section>
  )
}

function EmptyState({ text }) {
  return <div className="empty"><Sparkles size={30} /><p>{text}</p></div>
}

function HistoryCard({ item }) {
  return (
    <article className="history-card">
      <div className="history-sprite"><Sprite name={item.mvp} /></div>
      <div><h4>{item.mvp}</h4><span><MapPin size={13} /> {item.map}</span></div>
      <div><small>Matado por</small><b>{item.character}</b></div>
      <time>{item.event_time || item.detected_at}</time>
    </article>
  )
}

const emptyMvp = () => ({
  name: '', respawn_min_minutes: '', respawn_max_minutes: '',
  shared_spawn_group: '', maps: [{ map_name: '', respawn_min_minutes: '', respawn_max_minutes: '' }],
})

function CatalogModal({ item, csrfToken, onClose, onSaved, onDeleted }) {
  const editing = Boolean(item?.id)
  const [form, setForm] = useState(() => item ? {
    ...item,
    shared_spawn_group: item.shared_spawn_group || '',
    maps: item.maps.map((map) => ({ ...map })),
  } : emptyMvp())
  const [sprite, setSprite] = useState(null)
  const [saving, setSaving] = useState(false)
  const [message, setMessage] = useState('')
  const [confirmDelete, setConfirmDelete] = useState(false)

  const updateMap = (index, field, value) => setForm((current) => ({
    ...current,
    maps: current.maps.map((map, position) => position === index ? { ...map, [field]: value } : map),
  }))
  const addMap = () => setForm((current) => ({
    ...current,
    maps: [...current.maps, {
      map_name: '',
      respawn_min_minutes: current.respawn_min_minutes,
      respawn_max_minutes: current.respawn_max_minutes,
    }],
  }))
  const removeMap = (index) => setForm((current) => ({
    ...current, maps: current.maps.filter((_, position) => position !== index),
  }))

  const submit = async (event) => {
    event.preventDefault()
    setSaving(true)
    setMessage('')
    try {
      const body = new FormData()
      body.append('data', JSON.stringify(form))
      if (sprite) body.append('sprite', sprite)
      const response = await apiFetch(editing ? `/api/catalog/${item.id}` : '/api/catalog', {
        method: editing ? 'PUT' : 'POST', body,
        headers: { 'X-CSRF-Token': csrfToken },
      })
      const data = await response.json()
      if (!response.ok) throw new Error(data.error || 'No se pudo guardar')
      await onSaved(data.message)
      onClose()
    } catch (error) {
      setMessage(error.message)
    } finally {
      setSaving(false)
    }
  }

  const deleteMvp = async () => {
    setSaving(true)
    setMessage('')
    try {
      const response = await apiFetch(`/api/catalog/${item.id}`, {
        method: 'DELETE', headers: { 'X-CSRF-Token': csrfToken },
      })
      const data = await response.json()
      if (!response.ok) throw new Error(data.error || 'No se pudo eliminar')
      await onDeleted(data.message)
      onClose()
    } catch (error) {
      setMessage(error.message)
      setConfirmDelete(false)
    } finally {
      setSaving(false)
    }
  }

  return <div className="modal-backdrop catalog-backdrop" onMouseDown={() => !saving && onClose()}>
    <form className="catalog-modal" onSubmit={submit} onMouseDown={(event) => event.stopPropagation()}>
      <button type="button" className="modal-close" onClick={onClose} disabled={saving} aria-label="Cerrar"><X size={18} /></button>
      <small>Catálogo de MVP</small>
      <h2>{editing ? `Editar ${item.name}` : 'Agregar un MVP'}</h2>
      <p className="form-help">El nombre y el mapa deben coincidir exactamente con los publicados por Sakura RO.</p>
      <div className="catalog-fields">
        <label className="wide">Nombre del MVP<input required value={form.name} onChange={(event) => setForm({ ...form, name: event.target.value })} /></label>
        <label>Respawn mínimo (min)<input required type="number" min="0" value={form.respawn_min_minutes} onChange={(event) => setForm({ ...form, respawn_min_minutes: event.target.value })} /></label>
        <label>Respawn máximo (min)<input required type="number" min="0" value={form.respawn_max_minutes} onChange={(event) => setForm({ ...form, respawn_max_minutes: event.target.value })} /></label>
        <label className="wide">Grupo compartido <span>(opcional)</span><input value={form.shared_spawn_group} onChange={(event) => setForm({ ...form, shared_spawn_group: event.target.value })} placeholder="Ej. bio3" /></label>
        <label className="wide gif-field"><ImageUp size={18} /><span>GIF del MVP <em>Opcional, máximo 5 MB</em></span><input type="file" accept="image/gif,.gif" onChange={(event) => setSprite(event.target.files?.[0] || null)} /></label>
      </div>
      <div className="maps-editor">
        <div className="maps-title"><div><b>Mapas asociados</b><small>Puede usar tiempos distintos al respawn base.</small></div><button type="button" onClick={addMap}><Plus size={14} /> Agregar mapa</button></div>
        {form.maps.map((map, index) => <div className="map-row" key={map.id || `new-${index}`}>
          <label>Mapa<input required value={map.map_name} onChange={(event) => updateMap(index, 'map_name', event.target.value)} placeholder="prontera" /></label>
          <label>Mínimo<input required type="number" min="0" value={map.respawn_min_minutes} onChange={(event) => updateMap(index, 'respawn_min_minutes', event.target.value)} /></label>
          <label>Máximo<input required type="number" min="0" value={map.respawn_max_minutes} onChange={(event) => updateMap(index, 'respawn_max_minutes', event.target.value)} /></label>
          <button type="button" className="remove-map" onClick={() => removeMap(index)} disabled={form.maps.length === 1} aria-label="Quitar mapa"><Trash2 size={16} /></button>
        </div>)}
      </div>
      {message && <div className="form-error">{message}</div>}
      {confirmDelete && <div className="delete-warning"><AlertTriangle size={18} /><span>Se eliminará el MVP y sus timers activos. El historial permanecerá.</span><button type="button" onClick={deleteMvp} disabled={saving}>Confirmar eliminación</button></div>}
      <div className="catalog-actions">
        {editing && !confirmDelete && <button type="button" className="delete-mvp" onClick={() => setConfirmDelete(true)}><Trash2 size={15} /> Eliminar MVP</button>}
        <span />
        <button type="button" className="cancel-button" onClick={onClose} disabled={saving}>Cancelar</button>
        <button type="submit" className="save-mvp" disabled={saving}><Save size={15} /> {saving ? 'Guardando…' : 'Guardar'}</button>
      </div>
    </form>
  </div>
}

function LoginModal({ onClose, onLogin }) {
  const [username, setUsername] = useState('')
  const [password, setPassword] = useState('')
  const [loading, setLoading] = useState(false)
  const [message, setMessage] = useState('')
  const submit = async (event) => {
    event.preventDefault()
    setLoading(true)
    setMessage('')
    try {
      const response = await apiFetch('/api/auth/login', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ username, password }),
      })
      const data = await response.json()
      if (!response.ok) throw new Error(data.error || 'No se pudo iniciar sesión')
      onLogin(data)
      onClose()
    } catch (error) {
      setMessage(error.message)
    } finally {
      setLoading(false)
    }
  }
  return <div className="modal-backdrop" onMouseDown={() => !loading && onClose()}>
    <form className="login-modal" onSubmit={submit} onMouseDown={(event) => event.stopPropagation()}>
      <button type="button" className="modal-close" onClick={onClose} disabled={loading} aria-label="Cerrar"><X size={18} /></button>
      <div className="login-shield"><ShieldCheck size={28} /></div>
      <small>Acceso administrativo</small>
      <h2>Iniciar sesión</h2>
      <p>El tracker continúa disponible para todos. Esta sesión habilita la administración del catálogo.</p>
      <label>Usuario<input autoComplete="username" required autoFocus value={username} onChange={(event) => setUsername(event.target.value)} /></label>
      <label>Contraseña<input type="password" autoComplete="current-password" required value={password} onChange={(event) => setPassword(event.target.value)} /></label>
      {message && <div className="form-error">{message}</div>}
      <button className="login-submit" type="submit" disabled={loading}><LogIn size={16} /> {loading ? 'Ingresando…' : 'Ingresar'}</button>
    </form>
  </div>
}

function CatalogView({ catalog, onAdd, onEdit, onBack }) {
  const [query, setQuery] = useState('')
  const filtered = useMemo(() => {
    const term = query.trim().toLowerCase()
    if (!term) return catalog
    return catalog.filter((item) =>
      `${item.name} ${item.maps.map((map) => map.map_name).join(' ')}`.toLowerCase().includes(term)
    )
  }, [catalog, query])
  const mapCount = catalog.reduce((total, item) => total + item.maps.length, 0)
  const customGifCount = catalog.filter((item) => item.sprite_url).length

  return <section className="catalog-page" aria-labelledby="catalog-title">
    <div className="catalog-page-heading">
      <div>
        <button className="back-to-tracker" onClick={onBack}><RotateCcw size={14} /> Volver al tracker</button>
        <p>Administración</p>
        <h2 id="catalog-title">Catálogo de MVP</h2>
        <span>Consulta y administra todos los MVP, incluso cuando no tienen un timer activo.</span>
      </div>
      <button className="catalog-add-main" onClick={onAdd}><Plus size={17} /> Agregar un MVP</button>
    </div>

    <div className="catalog-summary">
      <div><b>{catalog.length}</b><span>MVP registrados</span></div>
      <div><b>{mapCount}</b><span>Mapas asociados</span></div>
      <div><b>{customGifCount}</b><span>GIF personalizados</span></div>
    </div>

    <div className="catalog-toolbar">
      <div className="search"><Search size={18} /><input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Buscar por MVP o mapa..." /></div>
      <span>{filtered.length} resultado{filtered.length === 1 ? '' : 's'}</span>
    </div>

    <div className="catalog-table-wrap">
      <table className="catalog-table">
        <thead><tr><th>MVP</th><th>Mapas asociados</th><th>Respawn base</th><th>Grupo</th><th>Acciones</th></tr></thead>
        <tbody>{filtered.map((item) => <tr key={item.id}>
          <td data-label="MVP"><div className="catalog-identity"><div><Sprite name={item.name} customSource={item.sprite_url} /></div><span><b>{item.name}</b><small>ID #{item.id}</small></span></div></td>
          <td data-label="Mapas"><div className="catalog-map-list">{item.maps.map((map) => <span key={map.id}>{map.map_name}{map.is_override ? <em>{map.respawn_min_minutes}–{map.respawn_max_minutes} min</em> : null}</span>)}</div></td>
          <td data-label="Respawn"><strong className="catalog-respawn">{item.respawn_min_minutes}–{item.respawn_max_minutes} min</strong></td>
          <td data-label="Grupo"><span className="catalog-group">{item.shared_spawn_group || '—'}</span></td>
          <td data-label="Acciones"><button className="catalog-edit" onClick={() => onEdit(item)}><Edit3 size={14} /> Editar</button></td>
        </tr>)}</tbody>
      </table>
      {!filtered.length && <div className="catalog-empty"><Search size={25} /><p>No encontramos MVP con esa búsqueda.</p></div>}
    </div>
  </section>
}

export default function App() {
  const { timers, history, connected, lastUpdate } = useTrackerData()
  const { catalog, loadCatalog } = useCatalog()
  const [query, setQuery] = useState('')
  const [status, setStatus] = useState('all')
  const [resetOpen, setResetOpen] = useState(false)
  const [aboutOpen, setAboutOpen] = useState(false)
  const [resetting, setResetting] = useState(false)
  const [resetMessage, setResetMessage] = useState('')
  const [catalogModal, setCatalogModal] = useState(null)
  const [timerToReset, setTimerToReset] = useState(null)
  const [loginOpen, setLoginOpen] = useState(false)
  const [auth, setAuth] = useState({ authenticated: false, username: null, csrf_token: null })
  const [view, setView] = useState('tracker')

  useEffect(() => {
    apiFetch('/api/auth/status', { cache: 'no-store' })
      .then((response) => response.json())
      .then(setAuth)
      .catch(() => {})
  }, [])

  const catalogByName = useMemo(
    () => Object.fromEntries(catalog.map((item) => [item.name, item])),
    [catalog],
  )

  const showMessage = (message) => {
    setResetMessage(message)
    setTimeout(() => setResetMessage(''), 3500)
  }

  const resetTimers = async () => {
    setResetting(true)
    try {
      const response = await apiFetch('/api/reset', { method: 'POST' })
      if (!response.ok) throw new Error('No se pudieron reiniciar los timers')
      setResetOpen(false)
      setResetMessage('Timers reiniciados correctamente')
      setTimeout(() => setResetMessage(''), 3500)
    } catch {
      setResetMessage('Ocurrió un error al reiniciar los timers')
      setTimeout(() => setResetMessage(''), 3500)
    } finally {
      setResetting(false)
    }
  }

  const resetOneTimer = async () => {
    if (!timerToReset) return
    setResetting(true)
    try {
      const response = await apiFetch('/api/timers/reset-one', {
        method: 'POST', headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id: timerToReset.id }),
      })
      const data = await response.json()
      if (!response.ok) throw new Error(data.error || 'No se pudo reiniciar el timer')
      setTimerToReset(null)
      showMessage(`Timer de ${timerToReset.mvp} reiniciado correctamente`)
    } catch (error) {
      showMessage(error.message)
    } finally {
      setResetting(false)
    }
  }

  const catalogChanged = async (message) => {
    await loadCatalog()
    showMessage(message)
  }

  const logout = async () => {
    const response = await apiFetch('/api/auth/logout', {
      method: 'POST', headers: { 'X-CSRF-Token': auth.csrf_token || '' },
    })
    if (response.ok) {
      setAuth({ authenticated: false, username: null, csrf_token: null })
      setCatalogModal(null)
      setView('tracker')
      showMessage('Sesión administrativa cerrada')
    }
  }

  const priorityTimers = useMemo(() => timers.filter(PRIORITY).sort(sortByNextSpawn), [timers])
  const filteredTimers = useMemo(() => timers
    .filter((timer) => status === 'all' || getState(timer) === status)
    .filter((timer) => `${timer.mvp} ${timer.map}`.toLowerCase().includes(query.toLowerCase()))
    .sort(sortByNextSpawn), [timers, query, status])

  const aliveCount = timers.filter((timer) => timer.is_alive).length
  const windowCount = timers.filter((timer) => timer.in_delay_window && !timer.is_alive).length

  return (
    <div className={`app-shell ${view === 'catalog' ? 'catalog-mode' : ''}`}>
      <header className="hero">
        <div className="hero-orb orb-one" /><div className="hero-orb orb-two" />
        <nav>
          <div className="brand"><Swords /><span>SAKURA <b>RO</b></span></div>
          <div className="nav-actions">
            {auth.authenticated && <div className="admin-navigation">
              <button className={view === 'tracker' ? 'active' : ''} onClick={() => setView('tracker')}>Tracker</button>
              <button className={view === 'catalog' ? 'active' : ''} onClick={() => setView('catalog')}>Catálogo de MVP</button>
            </div>}
            {auth.authenticated && <button className="add-mvp-button" onClick={() => setCatalogModal({ mode: 'create' })}><Plus size={15} /> Agregar un MVP</button>}
            {auth.authenticated
              ? <button className="session-button admin" onClick={logout}><LogOut size={15} /> {auth.username}</button>
              : <button className="session-button" onClick={() => setLoginOpen(true)}><LogIn size={15} /> Iniciar sesión</button>}
            <button className="about-button" onClick={() => setAboutOpen(true)}><Info size={15} /> Acerca del tracker</button>
            <div className={`connection ${connected ? 'online' : ''}`}><i />{connected ? 'Actualizando datos' : 'Sin conexión'}</div>
          </div>
        </nav>
        <div className="hero-copy">
          <div><p className="kicker">Sakura RO · MVP monitor</p><h1>MVP Tracker<br /><span>Bratva</span></h1><p>Seguimiento automático de respawns para la guild.</p></div>
          <div className="hero-stats">
            <div><b>{timers.length}</b><span>Timers</span></div>
            <div><b>{aliveCount}</b><span>Disponibles</span></div>
            <div><b>{windowCount}</b><span>Delay</span></div>
          </div>
        </div>
        <div className="last-sync">Última sincronización: {lastUpdate ? lastUpdate.toLocaleTimeString('es-MX') : '--:--:--'}</div>
      </header>

      <main className={view === 'catalog' ? 'catalog-main' : ''}>
        {view === 'catalog' && auth.authenticated ? <CatalogView
          catalog={catalog}
          onAdd={() => setCatalogModal({ mode: 'create' })}
          onEdit={(item) => setCatalogModal({ mode: 'edit', item })}
          onBack={() => setView('tracker')}
        /> : <>
        <Section icon={<img src={appPath('/mvps/mvp-icon.png')} alt="MVP" />} eyebrow="Seguimiento especial" title="Prioridad de la guild" count={priorityTimers.length} accent="red">
          <div className="timer-grid priority-grid">{priorityTimers.length ? priorityTimers.map((timer) => <TimerCard key={`p-${timer.id}`} timer={timer} priority catalogItem={catalogByName[timer.mvp]} canEdit={auth.authenticated} onEdit={(item) => setCatalogModal({ mode: 'edit', item })} onReset={setTimerToReset} />) : <EmptyState text="Todavía no hay timers prioritarios activos." />}</div>
        </Section>

        <div className="toolbar">
          <div className="search"><Search size={18} /><input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Buscar MVP o mapa..." /></div>
          <div className="toolbar-actions">
            <div className="filters">{[['all','Todos'],['waiting','Respawn'],['window','Delay'],['alive','Alive']].map(([value,label]) => <button key={value} className={status === value ? 'active' : ''} onClick={() => setStatus(value)}>{label}</button>)}</div>
            <button className="reset-button" onClick={() => setResetOpen(true)}><RotateCcw size={16} /> Reiniciar timers</button>
          </div>
        </div>

        <Section icon={<Clock3 />} eyebrow="Cuentas regresivas" title="Timers activos" count={filteredTimers.length} accent="blue">
          <div className="timer-grid">{filteredTimers.length ? filteredTimers.map((timer) => <TimerCard key={timer.id} timer={timer} catalogItem={catalogByName[timer.mvp]} canEdit={auth.authenticated} onEdit={(item) => setCatalogModal({ mode: 'edit', item })} onReset={setTimerToReset} />) : <EmptyState text="No hay timers que coincidan con los filtros." />}</div>
        </Section>

        <Section icon={<History />} eyebrow="Registro del servidor" title="Últimos MVP registrados" count={history.length} accent="gold" openDefault={false}>
          <div className="history-list">{history.slice(0, 40).map((item, index) => <HistoryCard key={`${item.event_time}-${item.mvp}-${index}`} item={item} />)}</div>
        </Section>
        </>}
      </main>
      <footer><span>Bratva MVP Tracker v1.0</span><span>Copyright © 2026 Marco Alvarez</span></footer>

      {aboutOpen && <div className="modal-backdrop" onMouseDown={() => setAboutOpen(false)}>
        <div className="about-modal" role="dialog" aria-modal="true" aria-labelledby="about-title" onMouseDown={(event) => event.stopPropagation()}>
          <button className="modal-close" onClick={() => setAboutOpen(false)} aria-label="Cerrar"><X size={18} /></button>
          <small>Proyecto personal</small>
          <h2 id="about-title">Bratva MVP Tracker</h2>
          <p>Desarrollado para facilitar el seguimiento de MVP en Sakura RO. Los timers se generan automáticamente a partir del ranking público y solamente consideran los mapas oficiales configurados.</p>
          <div className="about-details"><span>Versión 1.0</span><span>Marco Alvarez · 2026</span></div>
        </div>
      </div>}

      {resetOpen && <div className="modal-backdrop" onMouseDown={() => !resetting && setResetOpen(false)}>
        <div className="reset-modal" role="dialog" aria-modal="true" aria-labelledby="reset-title" onMouseDown={(event) => event.stopPropagation()}>
          <button className="modal-close" onClick={() => setResetOpen(false)} disabled={resetting} aria-label="Cerrar"><X size={18} /></button>
          <div className="modal-alert"><AlertTriangle size={27} /></div>
          <small>Confirmar acción</small>
          <h2 id="reset-title">¿Reiniciar todos los timers?</h2>
          <p>Se eliminarán las cuentas regresivas actuales. El historial de muertes se conservará y los próximos MVP detectados crearán timers nuevos.</p>
          <div className="modal-actions">
            <button className="cancel-button" onClick={() => setResetOpen(false)} disabled={resetting}>Cancelar</button>
            <button className="confirm-reset" onClick={resetTimers} disabled={resetting}><RotateCcw size={16} className={resetting ? 'spinning' : ''} /> {resetting ? 'Reiniciando…' : 'Sí, reiniciar'}</button>
          </div>
        </div>
      </div>}
      {timerToReset && <div className="modal-backdrop" onMouseDown={() => !resetting && setTimerToReset(null)}>
        <div className="reset-modal" role="dialog" aria-modal="true" aria-labelledby="reset-one-title" onMouseDown={(event) => event.stopPropagation()}>
          <button className="modal-close" onClick={() => setTimerToReset(null)} disabled={resetting} aria-label="Cerrar"><X size={18} /></button>
          <div className="modal-alert"><AlertTriangle size={27} /></div>
          <small>Reset individual</small>
          <h2 id="reset-one-title">¿Reiniciar el timer de {timerToReset.mvp}?</h2>
          <p>Solo se eliminará este timer. El historial y las demás cuentas regresivas se conservarán; volverá a generarse en la próxima muerte detectada.</p>
          <div className="modal-actions">
            <button className="cancel-button" onClick={() => setTimerToReset(null)} disabled={resetting}>Cancelar</button>
            <button className="confirm-reset" onClick={resetOneTimer} disabled={resetting}><RotateCcw size={16} className={resetting ? 'spinning' : ''} /> {resetting ? 'Reiniciando…' : 'Sí, reiniciar'}</button>
          </div>
        </div>
      </div>}
      {catalogModal && <CatalogModal
        item={catalogModal.mode === 'edit' ? catalogModal.item : null}
        csrfToken={auth.csrf_token}
        onClose={() => setCatalogModal(null)}
        onSaved={catalogChanged}
        onDeleted={catalogChanged}
      />}
      {loginOpen && <LoginModal onClose={() => setLoginOpen(false)} onLogin={setAuth} />}
      {resetMessage && <div className={`reset-toast ${resetMessage.startsWith('Ocurrió') ? 'error' : ''}`}><CheckCircle2 size={18} /> {resetMessage}</div>}
    </div>
  )
}
