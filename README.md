# Doctrine Examples

The purpose of this repo is to provide examples of Doctrine usage on top of current documentation. 
Doctrine is a brilliant package but some of its theory and implementation sometimes can be explained better or fully
built examples can definitely give an extra element of clarity.

## Requirements & start up
You should be able to run this repo as long as you have `docker`, `make` and can run commands in a linux like 
`shell/bash`. Before any run ensure your shell has the UID available by running `export UID`, at least once, before any 
make command below.

If you have the above you can run command `make up` and it will bring up the containers running nginx, php, mysql,
composer and doctrine migrations. `make bash` will get you a shell in the php container and `make composer_bash` a
shell inside a composer container. 


## Example 1: One to Many and Many to One

As part of your application you might need to implement associations/relations between a business, reviews and 
users/authors that left them. As an architectural decision we have 

```
Business <- One to Many -> Review <- Many to One -> User
```

### Removing One To Many Reviews by removing reviews from Collection 
 
*Notice Cascade all and remove orphans is set to true*

Currently, Doctrine does not proactively remove, links or refreshes relationships when a Business is changes/updated, 
even when setting the cascade operation to all for both the first and second relationship. So for instance in this 
example we:
1. create a business
2. save it and fetch it from the repository
3. change its reviews - for instance if we have 4 reviews then slicing would allow to remove some of them
4. save again - this should update the business and remove all orphans

If you run the command `app:business:testrunner test-id=removeElementsFromOneToMany` you can see that the business is 
created as per steps 1 and 2.
However if you look at the database some of the Business to Review relationship are removed but not the actual Reviews 
and User that should be cascade, removed as orphans

Also if running the above again then the failure of 
```shell
[2023-06-23T12:00:49.616749+00:00] app.CRITICAL: App\Infrastructure\Persistence\Doctrine\BusinessRepository::save exception class:Doctrine\DBAL\Exception\UniqueConstraintViolationException when saving businessId: marks.eudora with error: An exception occurred while executing a query: SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'qui3' for key 'PRIMARY' sql: INSERT INTO user (id, name) VALUES (?, ?) params: {"1":"qui3","2":"Shayna Upton Jr."}
```
happens because indeed the user was not removed, but also later another error shows up when the modification is 
attempted and  it is

```shell
[2023-06-23T12:00:49.621627+00:00] app.CRITICAL: App\Infrastructure\Persistence\Doctrine\BusinessRepository::save exception class:Doctrine\ORM\Exception\EntityManagerClosed when saving businessId: marks.eudora with error: The EntityManager is closed. sql:  trace: #0 /var/www/html/vendor/doctrine/orm/lib/Doctrine/ORM/EntityManager.php(877) ...
```

I am looking for suggestion and confirmation of why doctrine is not managing to delete associated relations as described
at in the [orphanRemoval docs](https://www.doctrine-project.org/projects/doctrine-orm/en/2.15/reference/working-with-associations.html#orphan-removal)
Also notice that User is an entity that is set when Business -> Reviews are created. However these constraint violations
are also happening with the user entity. So I am looking for the logic to ensure that if a user exist it is reused, if 
it does not then he is created but also if the user became an unlinked to a review than it is deleted. Notice users 
mights still be linked to other reviews.


# Other notes

- you can `docker-compose ps` and look at the host port exposed by docker for mysql to connect to it and look at the rows
- there is a deleteAll command `app:business:testrunner test-id=deleteAll` available. It kinda works but again as above orphans are not removed so some reviews and users are left around.
