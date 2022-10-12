FROM nginx:1.14-alpine

RUN rm -rf /etc/nginx/conf.d/*

COPY ./docker/nginx/host.conf /etc/nginx/conf.d/default.conf

EXPOSE 80
