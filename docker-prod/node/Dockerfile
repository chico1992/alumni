FROM node:8.11.4-alpine

RUN apk update && apk add git

WORKDIR tmp

RUN git clone https://github.com/chico1992/alumni.git
RUN mv /tmp/alumni/node /home/node/app
RUN rm -Rf /tmp/alumni

WORKDIR /home/node/app

RUN NODE_ENV=production npm install
