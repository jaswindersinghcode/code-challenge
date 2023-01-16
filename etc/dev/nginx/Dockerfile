FROM debian:11

RUN apt-get update && apt-get install -y nginx

# Configure Nginx
ADD nginx.conf /etc/nginx/
ADD app.conf /etc/nginx/sites-available/
#RUN sed "/fastcgi_read_timeout 60s;/c\  fastcgi_read_timeout ${MAX_EXECUTION_TIME}s;" -i /etc/nginx/nginx.conf

RUN echo "upstream php-upstream { server php:9000; }" > /etc/nginx/conf.d/upstream.conf
RUN usermod -u 1000 www-data

# Configure the virtual host
RUN ln -s /etc/nginx/sites-available/app.conf /etc/nginx/sites-enabled/app
RUN rm /etc/nginx/sites-enabled/default

# Run Nginx
CMD ["nginx"]

EXPOSE 80
EXPOSE 443