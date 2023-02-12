const mysql = require('mysql2');

let connection = mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE
});

const connect = () => {
    connection.connect(function(err) {
        if (err) {
            return console.error('MySQL Error: ' + err.message);
        }
        console.log('Connected to the MySQL server.');
    });
};


const getRegisteredNumbersLike = (number) => {
    return connection.promise().query('SELECT `id` FROM `numeros` WHERE eliminado=0 AND numero LIKE ?;', ['%' + number]);
};


const storeMessage = (message, from) => {
    connection.promise().query('INSERT INTO `mensajes` (`reportado_por`, `mensaje`) VALUES (?, ?);', [from, message]);
};

module.exports = {
    connect, 
    storeMessage, 
    getRegisteredNumbersLike
}