Nette Web Project
=================

Graphql api for crud operation with docker image

Requirements
------------

This Web Project is compatible with Nette 3.1 and requires PHP 8.2.

Installation
------------

**Install docker image:** 

- docker-compose build --no-cache  
- docker-compose up -d

**Entry into  PHP-NetteServer:** 

- docker exec -i -t id_container bash

**After when entry intp PHP-NetteServer:**
 - cd api
 - mkdir temp
 - mkdir log
 - composer install
 - php vendor/bin/phoenix migrate

**After install:**

Generate open api yaml : http://api.localhost.sk/api/v1/docs/open-api
Next documentations in https://editor.swagger.io/ with generate yaml
