const mysql = require('mysql2');

let connection = mysql.createConnection({
    host: process.env.DB_HOST,
    user: process.env.DB_USERNAME,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_DATABASE
});

/**
 * Conecta con MySQL
 */
const connect = () => {
    connection.connect(function(err) {
        if (err) {
            return console.error('MySQL Error: ' + err.message);
        }
        console.log('Connected to the MySQL server.');
    });
};

/**
 * Devuelve números similares guardados.
 * @param {Número de whatsapp} number 
 * @returns 
 */
const getRegisteredNumbersLike = (number) => {
    return connection.promise().query('SELECT `id`, `number_from` FROM `phone_numbers` WHERE deleted=0 AND number LIKE ?;', ['%' + number]);
};

/**
 * Guarda el mensaje en 'mensajes'
 * @param {*} message 
 * @param {Remitente del mensaje} from 
 * @returns 
 */
const storeMessage = (message, from) => {
    return connection.promise().query('INSERT INTO `messages` (`from`, `message`) VALUES (?, ?);', [from, message]);
};

/**
 * Retorna todos los tickets abiertos (CARGANDO_DATOS) del usuario.
 * @param {user} id 
 * @returns 
 */
const getOpenTicketsFrom = async (id) => {
    return (await connection.promise().query('SELECT * FROM `tickets` WHERE (status="LOADING" AND `from`=?);', [id]))[0];
};

/**
 * Abre un nuevo ticket con el estado de LOADING
 * @param {*} from 
 * @param {Mensajes separados por coma} messages 
 * @returns 
 */
const openNewTicket = (from, messages) => {
    return connection.promise().query('INSERT INTO `tickets` (`from`, `messages`, `status`, `notes`) VALUES (?, ?, ?, ?);', 
        [from, messages, 'LOADING', '']
    );
};

/**
 * Agregar un mensaje a un ticket
 * @param {*} ticketId 
 * @param {*} messageId 
 */
const addNewMessageToTicket = (ticketId, messageId) => {
    connection.promise().query('UPDATE `tickets` SET `messages` = CONCAT(messages, ?), `created_at`=CURRENT_TIMESTAMP WHERE id=?;', [`,${messageId}`, ticketId]);
}

/**
 * Devuelve todos los tickets que están como LOADING
 * @returns 
 */
const getAllLoadingTickets = async () => {
    return (await connection.promise().query('SELECT * FROM `tickets` WHERE status="LOADING";'))[0];
};

/**
 * Mueve el ticket de LOADING a OPEN
 * @param {*} id 
 */
const moveTicketToOpen = (id) => {
    connection.promise().query('UPDATE `tickets` SET `status`="OPEN" WHERE id=?;', [id]);
};

const setNumberFrom = (id, from) => {
    console.log(from, id)
    connection.promise().query('UPDATE `phone_numbers` SET `number_from`=? WHERE id=?;', [from, id]);
};

const getNumberFromId = async (id) => {
    return (await connection.promise().query('SELECT * FROM `phone_numbers` WHERE id=?;', [id]))[0];
};

module.exports = {
    connect, 
    storeMessage, 
    getRegisteredNumbersLike,
    getOpenTicketsFrom,
    openNewTicket,
    addNewMessageToTicket,
    getAllLoadingTickets,
    moveTicketToOpen,
    setNumberFrom,
    getNumberFromId
}