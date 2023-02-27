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
    return (await connection.promise().query('SELECT * FROM `tickets` WHERE (status="CARGANDO" AND `from`=?);', [id]))[0];
};

/**
 * Abre un nuevo ticket con el estado de CARGANDO
 * @param {*} from 
 * @param {Mensajes separados por coma} messages 
 * @returns 
 */
const openNewTicket = (from, messages) => {
    return connection.promise().query('INSERT INTO `tickets` (`from`, `messages`, `status`, `notes`) VALUES (?, ?, ?, ?);', 
        [from, messages, 'CARGANDO', '']
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
 * Devuelve todos los tickets que están como CARGANDO
 * @returns 
 */
const getAllLoadingTickets = async () => {
    return (await connection.promise().query('SELECT * FROM `tickets` WHERE status="CARGANDO";'))[0];
};

/**
 * Mueve el ticket de CARGANDO a ABIERTO
 * @param {*} id 
 */
const moveTicketToOpen = (id) => {
    connection.promise().query('UPDATE `tickets` SET `status`="ABIERTO" WHERE id=?;', [id]);
};

/**
 * Asigna el valor real del número de teléfono al usuario.
 * @param {*} id 
 * @param {*} from 
 */
const setNumberFrom = (id, from) => {
    connection.promise().query('UPDATE `phone_numbers` SET `number_from`=? WHERE id=?;', [from, id]);
};

/**
 * Asigna la url de la foto de perfil.
 * @param {*} id 
 * @param {*} url 
 */
const setProfilePicURL = (id, url) => {
    connection.promise().query('UPDATE `phone_numbers` SET `profile_pic`=? WHERE id=?;', [url, id]);
};

const getNumberById = async (id) => {
    return (await connection.promise().query('SELECT * FROM `phone_numbers` WHERE id=?;', [id]))[0];
};

const getTicketById = async (id) => {
    return (await connection.promise().query('SELECT * FROM `tickets` WHERE id=?;', [id]))[0][0];
}

const deleteMessageById = (id) => {
    connection.promise().query('DELETE FROM `messages` WHERE id=?;', [id]);
}

const deleteTicketById = async (id) => {
    const ticket = await getTicketById(id);
    const messages = ticket.messages.split(',');
    
    messages.forEach((message) => {
        deleteMessageById(message);
    });

    connection.promise().query('DELETE FROM `tickets` WHERE id=?;', [id]);
}

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
    getNumberById,
    deleteTicketById,
    getTicketById,
    deleteMessageById,
    setProfilePicURL
}