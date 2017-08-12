FROM traws_php

RUN apk update && apk add mysql-client

RUN curl -OL https://raw.github.com/wp-cli/builds/gh-pages/phar/wp-cli.phar \
    && chmod +x wp-cli.phar \
    && mv wp-cli.phar /usr/local/bin/wp
