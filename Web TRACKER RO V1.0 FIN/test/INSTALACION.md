# Instalación de Sakura MVP Tracker

Esta carpeta contiene todo el proyecto. No necesitas copiar archivos de ninguna otra versión.

## 1. Subir el proyecto

Desde PowerShell, ubicado en `Web TRACKER RO`:

```powershell
scp -r ".\test" ubuntu@161.153.198.115:/home/ubuntu/
```

## 2. Prepararlo en el VPS

Conéctate al servidor:

```powershell
ssh ubuntu@161.153.198.115
```

Ejecuta:

```bash
sudo mkdir -p /var/www/sakura-tracker
sudo cp -r /home/ubuntu/test/. /var/www/sakura-tracker/
sudo chown -R ubuntu:www-data /var/www/sakura-tracker

cd /var/www/sakura-tracker
sudo apt update
sudo apt install -y python3-venv nginx
python3 -m venv venv
./venv/bin/pip install -r requirements.txt
```

## 3. Crear el servicio

```bash
sudo tee /etc/systemd/system/ravenmoor-tracker.service >/dev/null <<'EOF'
[Unit]
Description=Sakura MVP Tracker
After=network.target

[Service]
User=ubuntu
Group=www-data
WorkingDirectory=/var/www/sakura-tracker
ExecStart=/var/www/sakura-tracker/venv/bin/gunicorn --workers 1 --bind 127.0.0.1:8000 --timeout 30 app:app
Restart=always

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl daemon-reload
sudo systemctl enable --now ravenmoor-tracker
```

## 4. Configurar Nginx

```bash
sudo tee /etc/nginx/sites-available/sakura-tracker >/dev/null <<'EOF'
server {
    listen 80;
    listen [::]:80;
    server_name _;

    location / {
        proxy_pass http://127.0.0.1:8000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
EOF

sudo ln -sf /etc/nginx/sites-available/sakura-tracker /etc/nginx/sites-enabled/sakura-tracker
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
sudo ufw allow OpenSSH
sudo ufw allow "Nginx Full"
sudo ufw --force enable
```

Abre `http://161.153.198.115` en el navegador.

## Actualizar después

Vuelve a subir `test` con el comando del primer paso y ejecuta en el VPS:

```bash
sudo cp -r /home/ubuntu/test/. /var/www/sakura-tracker/
sudo chown -R ubuntu:www-data /var/www/sakura-tracker
sudo systemctl restart ravenmoor-tracker
```

## Comprobar que funciona

```bash
sudo systemctl status ravenmoor-tracker --no-pager
sudo systemctl status nginx --no-pager
curl -I http://127.0.0.1
```
