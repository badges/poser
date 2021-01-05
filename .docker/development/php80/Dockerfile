FROM pugx/poser:php80

USER root

WORKDIR "/application"

## To generate phar archive: https://github.com/box-project/box
RUN wget -q -O /usr/bin/box https://github.com/box-project/box/releases/download/3.11.0/box.phar && chmod +x /usr/bin/box
