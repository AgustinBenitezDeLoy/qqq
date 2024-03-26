const express = require('express');
const bodyParser = require('body-parser');
const mysql = require('mysql');
const sessionUtils = require('./sessionUtils');
const isAdmin = require('./middleware/isAdmin');
const registroRouter = require('./registro');
const loginRouter = require('./login'); // Asume que tienes configurado este router

const app = express();

app.use(bodyParser.json());
app.use(sessionUtils); // Utiliza el middleware de sesión definido en sessionUtils.js

// Configura la conexión a la base de datos MySQL como un pool
const pool = mysql.createPool({
    host: 'localhost',
    user: 'root', // Actualiza con tus credenciales de MySQL
    password: '', // Actualiza con tu contraseña de MySQL
    database: 'reentraste' // Asegúrate de usar el nombre correcto de tu base de datos
});

// Hacer el pool de conexiones disponible en todas las rutas a través de req.pool
app.use((req, res, next) => {
    req.pool = pool;
    next();
});

// Definir rutas
app.use('/registro', registroRouter);
app.use('/login', loginRouter);

// Ruta protegida que requiere permisos de administrador
app.get('/admin/panel', isAdmin, (req, res) => {
    res.send('Panel de administración accesible solo por administradores');
});

// Middleware para manejar rutas no encontradas
app.use((req, res, next) => {
    res.status(404).send('Lo sentimos, no podemos encontrar eso!');
});

// Middleware para manejar errores
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).send('Algo salió mal!');
});

// Iniciar el servidor
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`Servidor corriendo en el puerto ${PORT}`);
});
