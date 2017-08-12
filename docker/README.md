# Docker for Bedrock

## Initial Setup

The Docker setup uses a reverse nginx proxy to allow you to access the site through a normal url. 
You can setup one nginx-proxy that will work across all projects by taking the following steps:

### Create Docker Network

The Nginx Proxy will operate in its own network in order that it can connect to docker containers
created using the `docker-compose.yml` file.
 
To create the new network run

    docker network create --driver bridge reverse-proxy
    
### Create Reverse Nginx Proxy container

For the reverse nginx proxy container we will use the `jwilder/nginx-proxy`. We will want to run a 
custom config in order to support `phpmyadmin` and uploading larger files. Copy the following into 
a file called `proxy.conf`

    # HTTP 1.1 support
    proxy_http_version 1.1;
    proxy_buffering off;
    proxy_set_header Host $http_host;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection $proxy_connection;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $proxy_x_forwarded_proto;
    proxy_set_header X-Forwarded-Ssl $proxy_x_forwarded_ssl;
    proxy_set_header X-Forwarded-Port $proxy_x_forwarded_port;
    
    # Mitigate httpoxy attack (see README for details)
    proxy_set_header Proxy "";
    
    # ensure phpmyadmin works
    client_max_body_size 512m;

Once you have copied the above configuration into place, run the following command to launch the nginx 
proxy

    docker run -d --rm \
        --name nginx-proxy \ 
        --net reverse-proxy \
        -p 80:80 \
        -v /var/run/docker.sock:/tmp/docker.sock:ro \
        -v <path-to-proxy.conf>
        /proxy.conf:/etc/nginx/proxy.conf \
        jwilder/nginx-proxy
        
### Edit the hosts file

You will also need edit your hosts file so you can assign particular domains to the localhost IP address.
For Linux and MacOS, you can find you hosts file here

    /etc/hosts
    
For windows you will find your hosts file here

    C:\Windows\System32\drivers\etc\hosts

You will want to add the following two lines to the bottom of your file

    127.0.0.1 traws.local    # bedrock application
    127.0.0.1 db.traws.local # phpmyadmin for bedrock database
    127.0.0.1 mail.traws.local # maildev managing emails sent by the application
    
### Create configuration files

The docker environment comes with all the application environments that you need to get started. However
when you do need to add additional configuration, you can do it using the `.env` file in the `docker`
folder. 

To begin with you will want to copy the `.env.example` file to `.env`. The `COMPOSE_PROJECT_NAME` 
variable will ensure that the containers in this docker-compose configuration don't interfere with
other ones on your system. 

If you need to add further configuration for the application that should be specially setup for the
docker environment, you should add it to the `php/application.env` folder, which gets used in the 
`docker-compose.yml` file for all `php` based containers. If you need to add configuration for the
application that is not docker specific (e.g. a Google Maps API token), then you should use the 
`.env` file in the project folder (just as you would normally).

## Application containers
 
This docker setup uses `docker-compose` to provision the containers. There are three containers that are run
to provide the services needed for development. 

**PHP**

Based on the official `php` container, the `php/Dockerfile` adds additional commands to install required
PHP extensions for the project. Finally the default `CMD` to run when launching the container is the built-in
PHP server. 

When running, the site will be available at [http://traws.local](http://traws.local).

**Database**

This runs a simple `mariadb` container with a `volume` to store the MySQL data in the `database` folder locally.
Stopping this container or removing it won't delete any data, as the data will be stored in the `database` folder.

**Phpmyadmin**

Not required for running the application, but useful for managing the database. 

When running you can view and manage the contents of the database at 
[http://db.traws.local](http://db.traws.local)

**MailDev**

Not required for running the application, but useful for testing email functionality. 

When running you can view and manage all emails sent from the application at 
[http://mail.traws.local](http://mail.traws.local)

### Start application

Run the following command to create the containers for the application

    docker/bin/dev start
    
    docker/bin-windows/dev start  # for Windows machines
    
This creates the containers to run the application, builds the containers for the tools needed to develop
the application and runs `composer install` to set up the dependencies.

### Stop application

Run the following command to stop the containers for the application

    docker/bin/dev stop
    
    docker/bin-windows/dev stop  # for Windows machines
    
This simply stops the running applications containers.

## Tools

This docker setup comes with a number of tools for development.

### Composer container

To ensure that the container that runs composer is configured with the correct PHP version and extensions, 
it is built from the `PHP` container that runs the application. To run a composer command use the following
command

    docker/bin/composer <command>
    
    docker/bin-windows/composer <command>  # for Windows machines
    
### WP CLI container

WordPress sites can use the command-line interface provided by the `wp-cli/wp-cli` package. This
docker environment comes with a container with `wp-cli` installed.

    docker/bin/wp <command>
    
    docker/bin-windows/wp <command>  # for Windows machines
    
### PHP container

If you just want to run a php command, then we have you covered. 

    docker/bin/php <command>
    
    docker/bin-windows/php <command>  # for Windows machines
    
### Logs

All application containers will output their logs to the standard out, and you can view these by 
using the following command

    docker/bin/logs 
    
    docker/bin-windows/logs  # for Windows machines