# Bootstrap Backend

##  Info

    Ce repo permet d'instancier un projet en Symfony 4.x et PHP 7.x
    C'est un outil de développement qui ne convient pas à un usage en production.
    Cette version exploite le Webserver de SYMFONY et un container PHP
    

## Installation

### Pour instancier un nouveau projet

    Il vous faut a minima un php-cli et composer en local sur votre poste
    
    $ php -d memory_limit=-1 /usr/local/bin/composer create-project ygranger/docker_backend_bootstrap mon-projet \
      dev-master --stability=dev --no-secure-http --no-interaction \
      --repository='{"type": "vcs","url": "http://git:VHUvF4x4-cAaCXuHn7pJ@git.inter-invest.fr/ygranger/docker_backend_bootstrap.git" }'

    Le projet sera initialisé et prêt à l'emploi.
    
    N'oubliez pas d'initialiser git:

    $ cd <project name>
    $ git init
    $ git remote add origin <repository url>
        
    Le projet sera initialisé et prêt à l'emploi. 
    
    $ make start (stop)
    $ ouvrez l'url http://mon-projet.ii.localhost
    
## Pré-requis

    - Installer composer globalement : $ curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    - [Installer Docker CE](https://store.docker.com/search?offering=community&type=edition)

    Vous devez avoir installé le docker-env : http://git.inter-invest.fr/ygranger/docker_env
    Attention, lire le README.md avant de cloner le repo