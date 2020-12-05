# Symfony 5 App Boilerplate

A boilerplate for applications using Symfony 5.

## Setup
##### 1. Clone repository
``` bash
git clone git@github.com:arttraveller/symfony5-app-boilerplate.git
```
##### 2. Install dependencies
``` bash    
composer install
```                  
##### 3. Build docker services
``` bash
docker-compose build
```
##### 4. Start docker services
``` bash
docker-compose up -d
```
##### 5. Apply migrations
``` bash
make migrate
```   
##### 6. Load fixtures (optional)
``` bash 
make load-fixtures
```
##### 7. Generate SSL keys (optional, for API only)
* More details in [LexikJWTAuthenticationBundle](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation) documentation
* Set your JWT_PASSPHRASE in .env or .env.local file
* and run in terminal
``` bash 
openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
sudo chmod +r config/jwt/private.pem
```
##### 8. Run tests (optional, steps 6 and 7 required)
``` bash
make tests-all
```
