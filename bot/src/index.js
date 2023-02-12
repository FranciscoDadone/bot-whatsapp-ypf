const qrcode = require('qrcode-terminal');
const { Client, LocalAuth } = require('whatsapp-web.js');
const { connect, getRegisteredNumbersLike, storeMessage, getOpenTicketsFrom, openNewTicket } = require('./dbConnection');


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

client.on('qr', (qr) => {
    qrcode.generate(qr, {small: true});
});

client.on('ready', async () => {
    console.log('Client is ready!');
});

client.on('authenticated', async () => {
    console.log('Authenticated')
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

    // No hay números registrados a ese número.
    if (!numbersLike.length) return;
    const userId = numbersLike[0].id;

    const msgId = (await storeMessage(message.body, userId))[0].insertId;

    const openTicketFromUser = (await getOpenTicketsFrom(userId))[0];

    if (openTicketFromUser == undefined) { // No existe un ticket ya abierto cargando datos -> crear ticket
        const ticketId = (await openNewTicket(userId, msgId))[0].insertId;
        client.sendMessage(message.from,
            `✉️ Nuevo Ticket creado (*#${ticketId}*)! \nLo que sigas mandando en los próximos 5 minutos se va a cargar automáticamente a este ticket. \nPara cerrarlo escribe _cerrarticket_`
        )
    } else {

    }
})
