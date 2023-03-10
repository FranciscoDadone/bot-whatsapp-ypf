const qrcode = require('qrcode-terminal');
const { Client, LocalAuth } = require('whatsapp-web.js');
var fs = require('fs');
const {
    connect,
    getRegisteredNumbersLike,
    storeMessage,
    getOpenTicketsFrom,
    openNewTicket,
    getAllLoadingTickets,
    moveTicketToOpen,
    setNumberFrom,
    getNumberById,
    deleteTicketById,
    setProfilePicURL,
    getRegisteredUsersNumbersLike,
    setNumberFromUser,
    getNotifications,
    deleteNotificationById
} = require('./dbConnection');


const client = new Client({
    authStrategy: new LocalAuth(),
    puppeteer: {
        headless: false,
        args: ['--user-agent=Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36'],
        browserWSEndpoint: process.env.BROWSER_URL,
        executablePath:'/usr/bin/google-chrome',
    },
});

connect();

console.log("Starting bot...")

const init = async () => {
    client.initialize();
};
setTimeout(init, 5000);

const checkLoadingTicket = async () => {
    const loadingTickets = await getAllLoadingTickets();
    const notifications = await getNotifications();

    loadingTickets.forEach(async (ticket) => {
        const dateDiff = ((new Date() - ticket.created_at) / 1000) / 60;
        if (dateDiff >= 5) {
            moveTicketToOpen(ticket.id);
            const numberFrom = (await getNumberById(ticket.from))[0].number_from;
            client.sendMessage(numberFrom,
                `Ticket *#${ticket.id}* guardado!`
            );
        }
    });

    notifications.forEach(async (notification) => {
        client.sendMessage(notification.phone_number, notification.message);
        deleteNotificationById(notification.id);
    })

    setTimeout(checkLoadingTicket, 60000);
}

client.on('qr', (qr) => {
    qrcode.generate(qr, {small: true});
});

client.on('ready', async () => {
    console.log('Client is ready!');
    checkLoadingTicket();
});

client.on('authenticated', async () => {
    console.log('Authenticated');
})

client.on('authentication_failure', async (message) => {
    console.log('Auth failed: ', message)
})
client.on('loading_screen', async (message) => {
    console.log('Loading... ', message)
})
client.on('state_changed', async (message) => {
    console.log('State changed: ', message)
})

client.on('message', async (message) => {

    const number = message.from.split('@')[0].substring(3);
    const numbersLike = (await getRegisteredNumbersLike(number))[0];
    const numbersLikeUsers = (await getRegisteredUsersNumbersLike(number))[0];

    if (message.type == 'chat' && message.body.toLowerCase() == 'verificar' && numbersLikeUsers[0] && (numbersLikeUsers[0].number_from == null || numbersLikeUsers[0].number_from == '')) {
        setNumberFromUser(numbersLikeUsers[0].id, message.from);
        client.sendMessage(message.from, 'N??mero verificado correctamente!');
        return;
    }

    // No hay n??meros registrados a ese n??mero.
    if (!numbersLike.length || (
        message.type != 'chat' &&
        message.type != 'image' &&
        message.type != 'document' &&
        message.type != 'video' &&
        message.type != 'location'
        )) return;
    if (numbersLike[0].number_from == null) setNumberFrom(numbersLike[0].id, message.from);
    const userId = numbersLike[0].id;
    let msgId = null;

    const openTicketFromUser = (await getOpenTicketsFrom(userId))[0];

    if (openTicketFromUser) {
        saveMessage(message, userId, openTicketFromUser);
    }

    // Cerrar ticket
    if (message.body.toLowerCase() == 'terminarticket' && openTicketFromUser) {
        moveTicketToOpen(openTicketFromUser.id);
        client.sendMessage(message.from,
            `Ticket *#${openTicketFromUser.id}* guardado!`
        );
        return;
    }

    // Eliminar ticket
    if (message.body.toLowerCase() == 'eliminarticket' && openTicketFromUser) {
        deleteTicketById(openTicketFromUser.id);
        client.sendMessage(message.from,
            `Ticket *#${openTicketFromUser.id}* eliminado.`
        );
        return;
    }

    if (openTicketFromUser == undefined) { // No existe un ticket ya abierto cargando datos -> crear ticket y actualizar foto de perfil
        const ticketId = (await openNewTicket(userId))[0].insertId;
        const openTicketFromUser = (await getOpenTicketsFrom(userId))[0];
        saveMessage(message, userId, openTicketFromUser);

        const contact = await message.getContact();
        const profilePicURL = await contact.getProfilePicUrl();
        setProfilePicURL(userId, profilePicURL);

        client.sendMessage(message.from,
            `?????? Nuevo Ticket creado (*#${ticketId}*)! \nLo que sigas mandando en los pr??ximos 5 minutos se va a cargar autom??ticamente a este ticket. \nPara cerrarlo escribe _terminarticket_ \n O para eliminarlo _eliminarticket_`
        )
    } else {
        client.sendMessage(message.from,
            `Mensaje agregado al Ticket *#${openTicketFromUser.id}* \nTu Ticket se cerrar?? autom??ticamente en 5 minutos.`
        );
    }
})

async function saveMessage(message, userId, openTicketFromUser) {
    if (message.hasMedia && (message.type == 'video' || message.type == 'image' || message.type == 'document')) {
        const media = await message.downloadMedia();
        let mediaType;

        switch (message.type) {
            case 'image':
                mediaType = 'png';
                break;
            case 'video':
                mediaType = 'mp4';
                break;
        }

        const filename = (media.filename) ? media.filename : `${userId}-${message.timestamp}.${mediaType}`;

        fs.writeFile(
            `../media/${filename}`,
            media.data,
            "base64",
            (err) => {
                if (err) {
                    console.log(err);
                }
            }
        );
        msgId = (await storeMessage(`media/${filename}`, userId, openTicketFromUser.id))[0].insertId;
    } else if (message.type == 'location') {
        msgId = (await storeMessage(`https://www.google.com/maps/search/?api=1&query=${message.location.latitude},${message.location.longitude}`, userId, openTicketFromUser.id))[0].insertId;
    } else if (message.type == 'chat') {
        msgId = (await storeMessage(message.body, userId, openTicketFromUser.id))[0].insertId;
    }
}
