FROM ubuntu:22.04

# Install PHP 8.1 and nginx build deps
ENV DEBIAN_FRONTEND=noninteractive
RUN apt update && \
    apt upgrade -y && \
    apt install -y build-essential wget libpcre3-dev zlib1g-dev libgd-dev php8.1-fpm

# Download nginx and ngx-fancyindex, compile and install it
RUN mkdir /build && cd /build && \
    wget https://nginx.org/download/nginx-1.24.0.tar.gz && \
    tar xvf nginx-1.24.0.tar.gz && \
    wget https://github.com/aperezdc/ngx-fancyindex/releases/download/v0.5.2/ngx-fancyindex-0.5.2.tar.xz && \
    tar xvf ngx-fancyindex-0.5.2.tar.xz && \
    cd nginx-1.24.0 && \
    ./configure --with-http_addition_module --with-http_image_filter_module --add-module=../ngx-fancyindex-0.5.2 && \
    make -j$(nproc) && \
    make install

# Copy our files
COPY ./icons /srv/http/icons
COPY ./fancyindex.conf /usr/local/nginx/conf/

# Configuration tweaks and other small ajustments
RUN ln -s /run/php-fpm/php-fpm.sock /run/php/php8.1-fpm.sock && \
    cd /usr/local/nginx/conf && \
    sed -i 's@    server {@    server {\n        set $root "//usr/local/nginx/html";\n        include fancyindex.conf;@' nginx.conf && \
    echo 'user www-data;' >> nginx.conf && \
    sed -i 's@/run/php-fpm/php-fpm.sock@/run/php/php8.1-fpm.sock@' fancyindex.conf && \
    cd ../html && \
    mv index.html default.html

# Tell docker we'll listen for requests on port 80
EXPOSE 80/tcp

# nginx and php-fpm will happily demonize by default, let them do so
CMD ["/bin/bash", "-c", "/usr/local/nginx/sbin/nginx && php-fpm8.1 && sleep infinity"]
