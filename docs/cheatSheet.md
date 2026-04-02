# Practical info

 ## Base user
        ["ROLE_USER","ROLE_ADMIN"]
        $2y$13$/hM1WMZb.vVl7p2MfOuA..1ebQ.9ok8iW91k8c5CArkHe3LAK2Yjy

 ## get file rights `(might need restart)`
        docker compose run --rm php chown -R $(id -u):$(id -g) .

 ## enter php container
        docker exec -it crm-webapp-php-1 bash
  ### create DB update from file changes
        php bin/console doctrine:migrations:diff
   #### apply update
        php bin/console doctrine:migrations:migrate

  ### clear cashe
        php bin/console cache:clear
  ### create/modify entity
        php bin/console make:entity
  ### test mailer config
        php bin/console mailer:test support@horten.fr

 ## build container
        docker compose build --pull --no-cache
 ## start container
        docker compose --env-file .env.local up --wait
 ## stop container
        docker compose down --remove-orphans
 ## show composer config
        docker compose --env-file .env.local config

# todo
 - configure SuperAdmin on first startup
 - setup rights restrictions
 - fix index filtering
 - setup materiel tree structure
 - figure out TLS : Open `https://localhost` in your favorite web browser and [accept the auto-generated TLS certificate](https://stackoverflow.com/a/15076602/1352334)