# Doctrine Examples

The purpose of this repo is to provide examples of how Doctrine usage on top of current documentation. 
Doctrine is a brilliant package but some of its theory and implementation sometimes can be explained better or fully
built examples can definitely give an extra element of clarity

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
Currently, Doctrine does not proactively remove, links or refreshes relationships when a Business is changes/updated, 
even when setting the cascade operation to all for both the first and second relationship. So for instance in this 
example we:
1. create a business
2. save it and fetch it from the repository
3. change its reviews
4. save again

If you run the command `bin/console app:business:test` you can see that the business is created as per steps 1 and 2.
Then after changing the business we attempt to save/persist it again. However, it errors with a unique constraint 
violation such as...

```shell
[2023-06-19T11:37:09.753632+00:00] app.CRITICAL: App\Infrastructure\Persistence\Doctrine\BusinessRepository::save exception class:Doctrine\DBAL\Exception\UniqueConstraintViolationException when saving businessId: stracke.rosario with error: An exception occurred while executing a query: SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'tempora' for key 'PRIMARY' sql: INSERT INTO business (id, alias, name, review_count, rating) VALUES (?, ?, ?, ?, ?) params: {"1":"tempora","2":"stracke.rosario","3":"Mrs. Zelma Labadie PhD_ChangeName","4":5,"5":3.5} 
```

I am looking for suggestion and confirmation of why doctrine is not aware that the object exists and so it tries to 
insert it again instead of updating it? 
Also notice that User is an entity that is set when Business -> Reviews are created. However these constraint violations
are also happening with the user entity. So I am looking for the logic to ensure that if a user exist it is reused, if 
it does not then he is created but also if the user becames a unlinked to a review that it is deleted. Notice users 
mights still be linked to other reviews.
