# How to run
###### Via docker
____
#### Pre-install
1. Mount composer image and install Laravel framework <br/> ```docker run --rm -v $(pwd):/app composer install```
2. Set **root** privileges to app directory <br/> ```sudo chown -R $USER:$USER .```
3. Copy Laravel's .env settings file <br/> ```cp .env.example .env```
#### Run project
1. Run docker service <br/> ```docker-compose up -d```
2. Generate app key <br/> ```docker-compose exec app php artisan key:generate```
3. Generate app cache <br/> ```docker-compose exec app php artisan config:cache```
