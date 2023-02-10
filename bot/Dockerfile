FROM node:12-slim

RUN apt-get update \
    && apt-get install -y wget gnupg libdrm2 libgbm1 git \
    && wget -q -O - https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' \
    && sh -c 'echo "deb [arch=amd64] http://deb.debian.org/debian bullseye main contrib non-free deb-src http://deb.debian.org/debian bullseye main contrib non-free" >> /etc/apt/sources.list.d/google.list' \
    && sh -c 'echo "deb [arch=amd64] http://deb.debian.org/debian-security/ bullseye-security main contrib non-free deb-src http://deb.debian.org/debian-security/ bullseye-security main contrib non-free" >> /etc/apt/sources.list.d/google.list' \
    && sh -c 'echo "deb [arch=amd64] http://deb.debian.org/debian bullseye-updates main contrib non-free deb-src http://deb.debian.org/debian bullseye-updates main contrib non-free" >> /etc/apt/sources.list.d/google.list' \
    && apt-get update && apt-get upgrade -y \
    && apt-get install -y libdrm2 libgbm1 \
    && apt-get install -y fonts-ipafont-gothic fonts-wqy-zenhei fonts-thai-tlwg fonts-kacst fonts-freefont-ttf libxss1 \
      --no-install-recommends 

# RUN apt-get update -qqy --no-install-recommends && apt-get install -qqy --no-install-recommends google-chrome-stable 
RUN apt-get update -y && apt-get install google-chrome-stable  -y

COPY ./package.json ./
COPY ./package-lock.json ./
COPY ./src ./src

RUN mkdir /.wwebjs_auth && mkdir /.wwebjs_auth/session

RUN npm i \
    && groupadd -r pptruser && useradd -r -g pptruser -G audio,video pptruser \
    && mkdir -p /home/pptruser/Downloads \
    && chown -R pptruser:pptruser /home/pptruser \
    && chown -R pptruser:pptruser /node_modules \
    && chown -R pptruser:pptruser /.wwebjs_auth \
    && chown -R pptruser:pptruser /.wwebjs_auth/session

USER pptruser

RUN npm i bufferutil@^4.0.1 utf-8-validate@^5.0.2 encoding@^0.1.0
