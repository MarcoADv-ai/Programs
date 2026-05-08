from flask import Flask, jsonify, send_from_directory
from flask_cors import CORS
import requests
from bs4 import BeautifulSoup
from datetime import datetime
import pytz
import re

app = Flask(__name__)
CORS(app)

# Zona horaria de Santiago, Chile
CHILE_TZ = pytz.timezone('America/Santiago')

# URL de Ravenmoor MVP ranking
RAVENMOOR_URL = "https://www.ravenmoor-ro.com/?module=ranking&action=mvp"

# Almacenar MVPs conocidos (en memoria)
known_mvps = set()

def fetch_mvp_data():
    """Obtiene los datos de MVPs desde Ravenmoor"""
    try:
        headers = {
            'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
        }
        response = requests.get(RAVENMOOR_URL, headers=headers, timeout=10)
        response.raise_for_status()
        return response.text
    except Exception as e:
        print(f"Error fetching data: {e}")
        return None

def parse_mvps(html_content):
    """Parsea la tabla de MVPs y devuelve una lista de MVPs"""
    if not html_content:
        return []
    
    soup = BeautifulSoup(html_content, 'html.parser')
    mvps = []
    
    # Buscar la tabla de MVPs
    table = soup.find('table')
    if not table:
        return []
    
    rows = table.find_all('tr')[1:]  # Saltar el header
    
    for row in rows:
        cols = row.find_all('td')
        if len(cols) >= 4:
            character_name = cols[0].get_text(strip=True)
            
            # Extraer el nombre del MVP del link
            mvp_link = cols[1].find('a')
            mvp_name = mvp_link.get_text(strip=True) if mvp_link else cols[1].get_text(strip=True)
            
            experience = cols[2].get_text(strip=True)
            map_name = cols[3].get_text(strip=True)
            
            mvps.append({
                'character': character_name,
                'mvp': mvp_name,
                'experience': experience,
                'map': map_name
            })
    
    return mvps

def create_mvp_id(mvp):
    """Crea un ID único para cada MVP basado en sus datos"""
    return f"{mvp['character']}|{mvp['mvp']}|{mvp['experience']}|{mvp['map']}"

@app.route('/')
def index():
    """Sirve la página principal"""
    return send_from_directory('.', 'index.html')

@app.route('/api/mvps')
def get_mvps():
    """Endpoint para obtener MVPs nuevos"""
    global known_mvps
    
    html = fetch_mvp_data()
    current_mvps = parse_mvps(html)
    
    new_mvps = []
    current_time = datetime.now(CHILE_TZ).strftime("%Y-%m-%d %H:%M:%S")
    
    # Detectar nuevos MVPs
    for mvp in current_mvps:
        mvp_id = create_mvp_id(mvp)
        
        if mvp_id not in known_mvps:
            mvp_with_time = mvp.copy()
            mvp_with_time['detected_at'] = current_time
            new_mvps.append(mvp_with_time)
            known_mvps.add(mvp_id)
    
    return jsonify({
        'new_mvps': new_mvps,
        'total_tracked': len(known_mvps)
    })

@app.route('/api/reset')
def reset_tracking():
    """Reinicia el tracking de MVPs"""
    global known_mvps
    known_mvps = set()
    return jsonify({'message': 'Tracking reset successfully', 'total_tracked': 0})

@app.route('/api/all')
def get_all_mvps():
    """Obtiene todos los MVPs actuales (para debug)"""
    html = fetch_mvp_data()
    current_mvps = parse_mvps(html)
    
    return jsonify({
        'mvps': current_mvps,
        'count': len(current_mvps)
    })

if __name__ == '__main__':
    import os
    port = int(os.environ.get('PORT', 5000))
    debug = os.environ.get('FLASK_ENV') != 'production'
    
    print("🚀 Servidor iniciado en http://localhost:{}".format(port))
    print("📊 Rastreando MVPs de Ravenmoor...")
    app.run(debug=debug, host='0.0.0.0', port=port)

