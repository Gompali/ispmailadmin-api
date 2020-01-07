# Mailserver API Backend

##  Info

    This repository is intended to add an API resource to a mailserver built following the
    ISP Mail tutorial : https://workaround.org/ispmail/buster/

## Installation
    
   
    Requirements are PHP > 7.1.3 and composer installed : if $ composer --version has no output
    
    ```
     $ curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer 
    ```
    
    Clone or download the repository under your web api directory : webmail.example.org/api
    Modify your apache configuration to expose the API. 

    Don't forget to forward mannualy Authorization header by adding in your configuration :
    RewriteEngine On
    RewriteCond %{HTTP:Authorization} ^(.*)
    RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]
    
    https://github.com/symfony/symfony/issues/19693
    
    1. First deploy the symfony app. Go to the root of this directory * (this project) and run :
    
        $ composer install --no-dev --optimize-autoloader
        $ APP_ENV=prod APP_DEBUG=0 php bin/console cache:clear
        $ chmod -R 777 var/cache
        $ chmod -R 777 var/log
        
    2. Export environment variables to your server :
    
        You can use secrets for your credentials: https://symfony.com/blog/new-in-symfony-4-4-encrypted-secrets-management        
        If you don't know about secrets, you would better use tranditionnal environment files :

        Copy .env file to .env.local on your production server, fill in missing values.   

        ## Values from tutorial :
        MAILADMIN_PASSWORD=zsgz8svd3ciBRISeJvqjzsgzzsgz
        MAILSERVER_PASSWORD=2OEWsABCtgRe6a0ovOcgAs2OEWssd
        
        ## Root credntials and host information:
        DATABASE_URL=mysql://root:zsgz8svd3ciBRISeJvqjzsgzzsgz@localhost:3306/mailserver
        
        ## Web api admin user
        ADMIN_PASSWORD='api-admin-user-password'
        ADMIN_USERNAME='api-admin-username'
                 
        ## Then Generate keys for JWT Token
        $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
        $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout                
     
                
     3. Generate model :
     
        First drop the database from tutorial
        $ bin/console d:d:d --force
        
        Create empty database
        $ bin/console d:d:c
        
        Rebuild schema with migrations
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