# Mailserver API Backend

##  Info

    This repository is intended to add an API resource to a mailserver built following the
    ISP Mail tutorial : https://workaround.org/ispmail/buster/

## Installation
    
    To use this repository you should clone or download it under your web directory :
    webmail.example.org/admin/api or whatever
    Add the alias in the apache https vhost.

    WARNING: IF YOU USE THIS API, THE DATABASE SCHEMA WILL BE REGENERATED AND DATABASE
    WILL BE EMPTIED ONCE   
    
    Requirements are PHP > 7.1.3 and composer installed : if $ composer --version has no output
    $ curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
    
    1. First deploy the symfony app. Go to the root of this directory * (this project) and run :
    
        $ composer install --no-dev --optimize-autoloader
        $ APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
        $ chmod -R 777 var/cache
        $ chmod -R 777 var/log
        
    2. Export environment variables to your server :
    
        If you're familiar and happy to use secrets, there's a new feature in Symfony that allowa that :
        https://symfony.com/blog/new-in-symfony-4-4-encrypted-secrets-management
        
        If you don't know about secrets, you would maybe have better use tranditionnal environment files :
        // Template is in .env file
    
        // Variables required : they come from tutorial
        MAILADMIN_PASSWORD=zsgz8svd3ciBRISeJvqjzsgzzsgz
        MAILSERVER_PASSWORD=2OEWsABCtgRe6a0ovOcgAs2OEWssd
        
        // These url contains credential of the database root user
        DATABASE_URL=mysql://root:zsgz8svd3ciBRISeJvqjzsgzzsgz@localhost:3306/mailserver
        
        // These values are used to create an API admin user
        ADMIN_PASSWORD='api-admin-user-password'
        ADMIN_USERNAME='api-admin-username'
                
        // Generate keys for JWT Token
        $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
        $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout                
     
                
     3. Generate model :
     
        First drop the database from tutorial
        $ bin/console d:d:d --force
        
        Then rebuild it
        $ bin/console d:d:m
        
        Create an admin account for API 
        $ bin/console create:admin
        

## Documentation

     ^/doc will display the API standard Open API 3 documentation
     
     All the routes are protected exept /login and /doc
     To use API you have to :
     
     - make a POST to /login request with admin credentials in the json body
     
        {
        	"username" : "api-admin-username",
        	"password" : "api-admin-user-password"
        }

     
     - if login succeed the api will return a Bearer token (a long string known as JWT Token)
     
     - use this JWT Token (expires after 1 hour) in authorization header and prefix "Bearer "

## Update Swagger

    docker run -i yousan/swagger-yaml-to-html < swagger.yaml > swagger.html