from flask import Flask, request, jsonify, render_template, redirect, url_for, session
from flask_sqlalchemy import SQLAlchemy
from werkzeug.security import generate_password_hash, check_password_hash
from datetime import datetime

app = Flask(__name__)
app.config["SQLALCHEMY_DATABASE_URI"] = "sqlite:///biblioteca.db"
app.config["SQLALCHEMY_TRACK_MODIFICATIONS"] = False
app.config['SECRET_KEY'] = 'tu_secreto_aqui'
db = SQLAlchemy(app)

# Modelo de Usuario
class Usuario(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(100), unique=True, nullable=False)
    password = db.Column(db.String(200), nullable=False)


# Modelo de Libro
class Libro(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    titulo = db.Column(db.String(100), nullable=False)
    autor = db.Column(db.String(100), nullable=False)
    anio = db.Column(db.Integer)
    cantidad = db.Column(db.Integer)  # Agregar esta línea

class Prestamo(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    libro_id = db.Column(db.Integer, db.ForeignKey('libro.id'), nullable=False)
    nombre = db.Column(db.String(100), nullable=False)
    sexo = db.Column(db.String(10), nullable=False)
    libro = db.relationship('Libro', backref='prestamos', lazy=True)

@app.route("/prestamos", methods=["GET"])
def obtener_prestamos():
    prestamos = Prestamo.query.all()  # Obtener todos los préstamos
    lista_prestamos = [{
        "id": prestamo.id,
        "nombre": prestamo.nombre,
        "libro": {
            "id": prestamo.libro.id,
            "titulo": prestamo.libro.titulo
        },
        "sexo": prestamo.sexo
    } for prestamo in prestamos]

    return jsonify(lista_prestamos)  # Devuelve los préstamos en formato JSON


@app.route("/")
def inicio():
    return render_template("inicio.html")

@app.route("/login", methods=["GET", "POST"])
def login():
    if request.method == "POST":
        data = request.get_json()
        usuario = Usuario.query.filter_by(username=data["username"]).first()
        if usuario and check_password_hash(usuario.password, data["password"]):
            session["usuario_id"] = usuario.id
            return jsonify({"mensaje": "Inicio de sesión exitoso"})
        return jsonify({"error": "Usuario no encontrado, regístrese por favor.", "mostrar_boton": True}), 401
    return render_template("login.html")

@app.route("/registro", methods=["GET", "POST"])
def registro():
    if request.method == "POST":
        data = request.get_json()
        usuario_existente = Usuario.query.filter_by(username=data["username"]).first()
        if usuario_existente:
            return jsonify({"error": "El usuario ya existe, Por favor inicie sesión.´", "mostrar_boton": True}), 400
        nuevo_usuario = Usuario(username=data["username"], password=generate_password_hash(data["password"]))
        db.session.add(nuevo_usuario)
        db.session.commit()
        return jsonify({"mensaje": "Registro exitoso"})
    return render_template("register.html")

@app.route("/biblioteca")
def biblioteca():
    if "usuario_id" not in session:
        return redirect(url_for("login"))
    return render_template("index.html")

@app.route("/logout")
def logout():
    session.pop("usuario_id", None)
    return redirect(url_for("inicio"))

@app.route("/agregar", methods=["POST"])
def agregar_libro():
    data = request.get_json()
    # Agregar el campo cantidad al crear el libro
    nuevo_libro = Libro(titulo=data["titulo"], autor=data["autor"], anio=data["anio"], cantidad=data["cantidad"])
    db.session.add(nuevo_libro)
    db.session.commit()
    return jsonify({"mensaje": "Libro agregado correctamente"}), 201


@app.route("/eliminar/<int:id>", methods=["DELETE"])
def eliminar_libro(id):
    libro = db.session.get(Libro, id)
    if libro:
        db.session.delete(libro)
        db.session.commit()
        return jsonify({"mensaje": "Libro eliminado correctamente"})
    return jsonify({"error": "Libro no encontrado"}), 404

@app.route("/actualizar/<int:id>", methods=["PUT"])
def actualizar_libro(id):
    libro = Libro.query.get(id)
    if libro:
        data = request.get_json()
        libro.titulo = data.get("titulo", libro.titulo)
        libro.autor = data.get("autor", libro.autor)
        libro.anio = data.get("anio", libro.anio)
        libro.cantidad = data.get("cantidad", libro.cantidad)  # Agregar cantidad aquí
        db.session.commit()
        return jsonify({"mensaje": "Libro actualizado correctamente"})
    return jsonify({"error": "Libro no encontrado"}), 404

@app.route("/libros", methods=["GET"])
def obtener_libros():
    libros = Libro.query.all()
    lista_libros = [{"id": libro.id, "titulo": libro.titulo, "autor": libro.autor, "anio": libro.anio, "cantidad": libro.cantidad} for libro in libros]
    return jsonify(lista_libros)



@app.route("/procesar_prestamo", methods=["POST"])
def procesar_prestamo():
    data = request.get_json()

    # Verificar qué datos recibimos
    print(f"Datos recibidos: {data}")  # Ver en la consola los datos

    # Validamos que los datos estén presentes
    if not data.get("nombre") or not data.get("sexo") or not data.get("libroId"):
        return jsonify({"mensaje": "Faltan datos", "error": True}), 400

    try:
        # Obtener el libro que se está prestando
        libro = Libro.query.get(data["libroId"])
        
        if not libro:  # Si el libro no existe
            return jsonify({"mensaje": "Libro no encontrado", "error": True}), 404

        if libro.cantidad <= 0:  # Si no hay más libros disponibles
            return jsonify({"mensaje": "No hay libros disponibles para este préstamo", "error": True}), 400
        
        # Reducir la cantidad del libro en 1
        libro.cantidad -= 1
        
        # Guardar los cambios en la base de datos
        db.session.commit()

        # Registrar el préstamo
        nuevo_prestamo = Prestamo(
            libro_id=data["libroId"],
            nombre=data["nombre"],
            sexo=data["sexo"]
        )
        
        db.session.add(nuevo_prestamo)
        db.session.commit()  # Confirmar cambios en la base de datos

        print(f"Préstamo guardado con ID: {nuevo_prestamo.id}")

        return jsonify({"mensaje": "Préstamo procesado con éxito"})
    
    except Exception as e:
        # Si ocurre un error, realizamos un rollback y lo mostramos
        db.session.rollback()
        print(f"Error al procesar el préstamo: {e}")  # Ver el error en la consola
        return jsonify({"mensaje": "Error al procesar el préstamo", "error": True}), 500


@app.route("/libros/<int:id>", methods=["GET"])
def obtener_libro(id):
    libro = Libro.query.get(id)
    if libro:
        return jsonify({
            "id": libro.id,
            "titulo": libro.titulo,
            "autor": libro.autor,
            "anio": libro.anio,
            "cantidad": libro.cantidad
        })
    return jsonify({"error": "Libro no encontrado"}), 404



if __name__ == "__main__":
    with app.app_context():
        db.create_all()
    app.run(debug=True)
