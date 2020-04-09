project with a version of symfony 4.4
to run a server on local lunch this commande: 
 execute this commande by step run project
  cd /new_project_sf4
 
  1) composer install
  2) php bin/console doctrine:database:create
  3) php bin/console doctrine:migrations:migrate
  4) php bin/console doctrine:fixtures:load --env=prod
  
  php -S 127.0.0.1:8000 -t public
 
 
