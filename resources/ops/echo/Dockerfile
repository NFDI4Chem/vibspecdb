FROM node:16.13.1-alpine3.13 AS assets-build

WORKDIR /var/www/html

COPY laravel-echo-server.json /var/www/html/

# Laravel-echo-server
RUN npm install -g --prod --no-lockfile laravel-echo-server
RUN npm i -g socket.io-client
  
EXPOSE 6001
CMD ["laravel-echo-server", "start"]