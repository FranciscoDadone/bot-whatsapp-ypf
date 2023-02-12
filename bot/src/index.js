const qrcode = require('qrcode-terminal');
const { Client, LocalAuth } = require('whatsapp-web.js');
const { connect, getRegisteredNumbersLike, storeMessage } = require('./dbConnection');


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

    storeMessage(message.body, numbersLike[0].id);
})
