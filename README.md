# test-leboncoin
Hello and welcome to my technical test for leboncoin.

###SETUP :
**WARNING : Do not try the Dockerfile, it does not work.**

- Open a terminal window.
- Clone the repository on your computer
```https://github.com/elisaparis/test-leboncoin.git```
- Once it's cloned, go to the repository
```cd test-leboncoin/leboncoin```
- Install the project :
```composer install```
- Once it's done, create the file .env.local
```touch .env.local```
- Paste the following code in the file :
```DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name```
- Replace the following values :

   `db_user` : your MySQL user
   
   `db_password` : the user's password
   
   `127.0.0.1:3306` : your local configuration for MySQL and the port
   
   `db_name` : the name of the DB (leboncoin)
   
- Go to your phpmyadmin / mysql, and create the database `leboncoin`
- In your terminal, enter the following command :
```php bin/console d:s:u --force```
- To initialize some data in the DB, enter :
``` php bin/console doctrine:fixtures:load```
- Now, to create a user for the project, use :
```php bin/console app:create:user```
and follow the instructions.

You are now ready to use the API.


###CURL CALLS

**Do not forget to use the right ids for the calls (user, categories, ads, ...)**

- Get all ads : 
```curl -X GET http://127.0.0.1:8000/api/ads```
- Create an ad :
```curl -X POST http://localhost:8000/api/ads \
     -H 'Content-Type: application/json' \
     -d '{ 
   	"user": 1,
   	"title": "My First Ad", 
   	"content": "This is an ad", 
   	"categories": [
   		1
   	],
   	"metas": [
   		{
   			"Salary": "70000"
   		},
   		{
   			"Contract": "CDI"
   		}
   	]
   }'
   ```
- Get an ad :
```curl -X GET http://localhost:8000/api/ads/1```

- Update an ad :
```
curl -X PUT http://localhost:8000/api/ads/23 \
  -H 'Content-Type: application/json' \
  -d '{ 
	"user": 1,
	"title": "New Title", 
	"content": "This is my new content", 
	"categories": [
		2
	],
	"metas": [
		{
			"Fuel type": "SP90"
		},
		{
			"Price": "7000"
		}
		
	]
}'
```

- Remove an ad :
```curl -X DELETE http://localhost:8000/api/ads/1```
