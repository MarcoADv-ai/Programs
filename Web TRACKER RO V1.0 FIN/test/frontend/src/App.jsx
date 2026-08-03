import { useEffect, useMemo, useState } from 'react'
import {
  AlertTriangle, CheckCircle2, ChevronDown, Clock3, Crown, History, MapPin,
  Info, RotateCcw, Search, Skull, Sparkles, Swords, UserRound, X,
} from 'lucide-react'

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
  (timer.mvp === 'Thanatos' && timer.map === 'thana_t12') ||
  (timer.mvp === "Nidhoggr's Shadow" && timer.map === '2@nyd') ||
  (timer.mvp === 'Wounded Morroc' && timer.map === 'moc_fild22')

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
          fetch(`/api/timers?t=${Date.now()}`, { cache: 'no-store' }),
          fetch(`/api/history?t=${Date.now()}`, { cache: 'no-store' }),
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

function Sprite({ name }) {
  const source = SPRITES[name]
  if (!source) {
    return <div className="sprite-fallback"><Skull size={42} /><span>MVP</span></div>
  }
  return <img className="mvp-sprite" src={source} alt={`Sprite de ${name}`} />
}

function TimerCard({ timer, priority = false }) {
  const state = getState(timer)
  const remaining = state === 'alive'
    ? 'ALIVE'
    : formatTime(state === 'window' ? timer.seconds_until_max : timer.seconds_until_delay)
  const elapsed = Math.max(0, timer.total_wait_seconds - timer.seconds_until_delay)
  const progress = state === 'alive'
    ? 100
    : Math.min(100, Math.max(0, (elapsed / timer.total_wait_seconds) * 100))
  const label = state === 'alive' ? 'Disponible' : state === 'window' ? 'Delay' : 'En respawn'

  return (
    <article className={`timer-card state-${state} ${priority ? 'is-priority' : ''}`}>
      <div className="card-glow" />
      {priority && <div className="priority-ribbon"><Crown size={13} /> Prioridad</div>}
      <div className="sprite-stage"><Sprite name={timer.mvp} /></div>
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
          <div><small>Delay</small><b>{timer.delay_window_start}</b></div>
        </div>
        <div className="respawn-note"><Clock3 size={14} /> Respawn {timer.respawn_min_minutes}–{timer.respawn_max_minutes} min</div>
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

export default function App() {
  const { timers, history, connected, lastUpdate } = useTrackerData()
  const [query, setQuery] = useState('')
  const [status, setStatus] = useState('all')
  const [resetOpen, setResetOpen] = useState(false)
  const [aboutOpen, setAboutOpen] = useState(false)
  const [resetting, setResetting] = useState(false)
  const [resetMessage, setResetMessage] = useState('')

  const resetTimers = async () => {
    setResetting(true)
    try {
      const response = await fetch('/api/reset', { method: 'POST' })
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

  const priorityTimers = useMemo(() => timers.filter(PRIORITY), [timers])
  const filteredTimers = useMemo(() => timers
    .filter((timer) => status === 'all' || getState(timer) === status)
    .filter((timer) => `${timer.mvp} ${timer.map}`.toLowerCase().includes(query.toLowerCase()))
    .sort((a, b) => {
      const order = { alive: 0, window: 1, waiting: 2 }
      const stateA = getState(a)
      const stateB = getState(b)
      const stateDifference = order[stateA] - order[stateB]
      if (stateDifference) return stateDifference
      if (stateA === 'window') return a.seconds_until_max - b.seconds_until_max
      if (stateA === 'waiting') return a.seconds_until_delay - b.seconds_until_delay
      return String(a.delay_window_end).localeCompare(String(b.delay_window_end))
    }), [timers, query, status])

  const aliveCount = timers.filter((timer) => timer.is_alive).length
  const windowCount = timers.filter((timer) => timer.in_delay_window && !timer.is_alive).length

  return (
    <div className="app-shell">
      <header className="hero">
        <div className="hero-orb orb-one" /><div className="hero-orb orb-two" />
        <nav><div className="brand"><Swords /><span>SAKURA <b>RO</b></span></div><div className="nav-actions"><button className="about-button" onClick={() => setAboutOpen(true)}><Info size={15} /> Acerca del tracker</button><div className={`connection ${connected ? 'online' : ''}`}><i />{connected ? 'Actualizando datos' : 'Sin conexión'}</div></div></nav>
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

      <main>
        <Section icon={<img src="/mvps/mvp-icon.png" alt="MVP" />} eyebrow="Seguimiento especial" title="Prioridad de la guild" count={priorityTimers.length} accent="red">
          <div className="timer-grid priority-grid">{priorityTimers.length ? priorityTimers.map((timer) => <TimerCard key={`p-${timer.id}`} timer={timer} priority />) : <EmptyState text="Todavía no hay timers prioritarios activos." />}</div>
        </Section>

        <div className="toolbar">
          <div className="search"><Search size={18} /><input value={query} onChange={(event) => setQuery(event.target.value)} placeholder="Buscar MVP o mapa..." /></div>
          <div className="toolbar-actions">
            <div className="filters">{[['all','Todos'],['waiting','Respawn'],['window','Delay'],['alive','Alive']].map(([value,label]) => <button key={value} className={status === value ? 'active' : ''} onClick={() => setStatus(value)}>{label}</button>)}</div>
            <button className="reset-button" onClick={() => setResetOpen(true)}><RotateCcw size={16} /> Reiniciar timers</button>
          </div>
        </div>

        <Section icon={<Clock3 />} eyebrow="Cuentas regresivas" title="Timers activos" count={filteredTimers.length} accent="blue">
          <div className="timer-grid">{filteredTimers.length ? filteredTimers.map((timer) => <TimerCard key={timer.id} timer={timer} />) : <EmptyState text="No hay timers que coincidan con los filtros." />}</div>
        </Section>

        <Section icon={<History />} eyebrow="Registro del servidor" title="Últimos MVP registrados" count={history.length} accent="gold" openDefault={false}>
          <div className="history-list">{history.slice(0, 40).map((item, index) => <HistoryCard key={`${item.event_time}-${item.mvp}-${index}`} item={item} />)}</div>
        </Section>
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
      {resetMessage && <div className={`reset-toast ${resetMessage.startsWith('Ocurrió') ? 'error' : ''}`}><CheckCircle2 size={18} /> {resetMessage}</div>}
    </div>
  )
}
