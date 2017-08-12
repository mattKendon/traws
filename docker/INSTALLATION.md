# Installation

This document will cover the steps that you will need to cover to 
get this docker environment running for a new environment. Then we 
will also cover the steps needed to cover to install it into an 
existing Bedrock project.

## New project

After copying the contents of the full project, you will need to 
make a number of changes to the docker project before running it
for the first time. 

### Project Name

The first thing to do is to change the `COMPOSE_PROJECT_NAME` value
in the `docker/.env`. You can do this, by copying the 
`docker/.env.example` file and calling it `docker/.env`.

In the `.env` file, you will find the following line

    COMPOSE_PROJECT_NAME=bedrock
    
And you will want to change it to some appropriate value for your project 
(the project name for example).

### Change container names

In order to be able to create custom containers, you will want to 
change the container names that are used when creating them. Run a
find & replace on all files in the `docker/` folder to find & 
replace 

    traws_
    <project-name>_
    
Where `<project-name>` is a unique name for your project. 

### Change URLs 

You will also want to change the url that the project appears under
and you can do that with another find & replace

    traws.local
    <project-url>.local
    
This will ensure that when you run the containers they will appear 
under that address. 

## Existing project

If you need to add this docker setup to an existing Bedrock project
here are the steps that you will need to take. 

### Copy Docker folder

After downloading both this project and the project that you want
to update, you will want to copy the `docker/` folder from this 
project to the other. 

### Copy server file

There is a custom `web/server.php` in this project, that will need
to be copied over to the `web/` folder in the existing project. This
is used to run the built-in PHP server in the docker containers. 

### Copy the Outlandish Smtp WordPress plugin

There is a single file `mu-plugin` in this project that should be
added to the existing project. You will need to copy it from 
`web/app/mu-plugins/outlandish-smtp.php` file to the same location
in the existing project. 

### Project Name

See section above.

### Change container names

See section above

### Change URLs

See section above

### Change volume directories 

By default the base of the Bedrock project is one directory above
the `docker/` folder, and the `docker-compose.yml` file is setup
in the same way. If your existing project has the same folder
structure, you will not need to change anything. 

However some Bedrock sites might have a different structure. An 
example of this is the following:

    project
      / docker
      / infrastructure
      / site
        / config
        / vendor
        / web
          / app
          / wp
          / index.php
          
 In the above, project, the `docker/` folder sits at the same level 
 as the `site/` folder, which contains the Bedrock project. 
 
 In such a scenario, you will need to change the following sections
 of code in the `docker-compose.yml`
 
     volumes:
       - ../:/app
       
You will want to change above to the following

    volumes:
      - ../site:/app
      
 This will mount the `site/` folder into the `/app` folder of the
 containers. 