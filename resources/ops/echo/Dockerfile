FROM node:16.13.1-alpine3.13 AS assets-build

# Laravel-echo-server
RUN npm global add --prod --no-lockfile laravel-echo-server \
    && npm cache clean
  
EXPOSE 6001
CMD ["laravel-echo-server", "start"]