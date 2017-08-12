IF "%1"=="start" (

    cd docker
    docker-compose up -d socialmonitor.local db.socialmonitor.local
    cd ..
    docker/bin-windows/composer install

) ELSE IF "%1"=="stop" (

    cd docker
    docker-compose stop
    cd ..

) ELSE (

    echo "Usage: %0 {start|reports|stop}"

)