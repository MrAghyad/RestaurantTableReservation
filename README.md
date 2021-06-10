# RestaurantTableReservation API
Laravel based API for restaurant table reservation that enables restaurant workers to easily reserve tables based on the number of seats requested by customers' groups.

## Features
The features of the API are the as follows:
* User Creation, Login, and Authentication
* Restaurant's table Creation, Deletion, and Retrieval
* Restaurant's table reservation creation
* Restaurant's table reservation cancellation
* Checking available time slots during the working day
* Retrieving restaurant's tables' reservations of the current day
* Retrieving the history of restaurant's tables' reservations
* All APIs are secured with JWT-baed Authentication/Authorization


## API Description
The API consists of several routes and endpoints. Most of the APIs are secured with JWT-Auth token. The source code of the APIs can be found in [laravel-rest/app/Http/Controllers](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/app/Http/Controllers). Moreover, the routes definition can be found in [laravel-rest/routes/api.php](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/routes/api.php).

In the sequel all APIs are categorized based on their controller class and listed along with their description, route, and authorization information. For more information refer to the source code at [laravel-rest/app/Http/Controllers](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/app/Http/Controllers) or to the [documentation](https://mraghyad.github.io/RestaurantTableReservationDocs).

<div align="center">
	<br>
	<img src="https://github.com/MrAghyad/RestaurantTableReservation/blob/main/TableReservation.png?raw=true" width="600">
	<br>
	<em>
	Figure: Endpoint diagram 
	</em>
</div>

### UserController [/source](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/app/Http/Controllers/UserController.php)
| Endpoint			| Description                    	| Authorization |  Route  	| Method |
| ------------- 		| -------------------------------------	| -------------- | -----------	| ------ |
| `store(Request $request)`     | Adds a new user       	 	|     admin	 |  /user  	|  post  |
| `login(Request $request)`   	| Logs an existing user in     		|     	 - 	 | /user/login  |  post	 |

### RestaurantTableController [/source](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/app/Http/Controllers/RestaurantTableController.php)
| Endpoint			| Description                    					| Authorization 	|  Route  	| Method |
| ------------- 		| ---------------------------------------------------------------------	| ---------------------	| -------------	| ------ |
| `store(Request $request)`     | Adds new restaurant table 						|     admin	 	|  /table  	|  post  |
| `index()`   			| Retrieves all restaurant tables 					|     admin 	 	|  /table  	|  get	 |
| `destroy($id)`   		| Removes the specified restaurant<br> table using a given table id 	|     admin 	 	|  /table/{id} 	| delete |


### ReservationController [/source](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/app/Http/Controllers/ReservationController.php)
| Endpoint				    | Description                    				   | Authorization   |  Route  		| Method |
| ----------------------------------------- | ------------------------------------------------------------ | ---------------- | -----------------------	| ------ |
| `store(Request $request)`     	    | Adds a new reservation	 				   |   admin/employee |  /reservation		|  post  |
| `getTodayReservations(Request $request)`  | Retruns a paginated listing of all reservations for today    |   admin/employee |  /reservation		|  get	 |
| `getAll(Request $request)`   		    | Returns a paginated listing of all reservations for all time, spciefied date range, or for spciefied tables ids  |   admin 	      |  /reservation/all	|  get	 |
| `checkAvailable($seats)`   		| Allow retrieving all restaurant tables 			   |   admin/employee 	      |  /reservation/available/{seats} 		|  get	 |
| `destroy($id)`   			    | Checks available time slots for today for a number of seats |   admin/employee |  /reservation/{id}       | delete |

#### Flow graphs - ReservationController
<div align="center">
	<img src="https://github.com/MrAghyad/RestaurantTableReservation/blob/main/TableReservationFlow_CheckAvailable.png?raw=true" width="200" height="200" hspace="10"/>
	<br>
	<em>
		Figure: Flow graph of ReservationController's checkAvailable
	</em>
	<p>
		(click on the figure to view)
	</p>
</div>

<div align="center">
	<img src="https://github.com/MrAghyad/RestaurantTableReservation/blob/main/TableReservationFlow_ReserveTable.png?raw=true" width="200" height="200" hspace="10"/>
	<br>
	<em>
		Figure: Flow graph of ReservationController's store
	</em>
	<p>
		(click on the figure to view)
	</p>
</div>
 

## Usage
Laravel was used to implement this project. The implementation can be found in ["laravel-rest" folder](https://github.com/MrAghyad/RestaurantTableReservation/tree/main/laravel-rest). To be able to fully use the API we discuss in this section details related to setting up the environment, database, and enabling testing.  

### Setting Up The Environment
-----------------------------
#### 1. Windows 10
-------------
The project was developed locally on a Windows 10 device. To run the project, Laravel and Postgresql have to be installed on your windows device. The installtion process of Laravel follows Laravel's official documentation for installation [using composer](https://laravel.com/docs/8.x#installation-via-composer), which assumes that your device  already has PHP and Composer installed. Nonetheless, to install PHP you can either:
- [Install PHP manually](https://www.php.net/manual/en/install.windows.manual.php)
- [Install XAMP](https://www.php.net/manual/en/install.windows.tools.php)

Moreover, to install Composer follow their [installation](https://getcomposer.org/download/) instructions.

On the other hand, the project utilizes Postgresql as database (however, you can implement whichever database system you prefer). To install Postgresql on Windows 10 you can follow the instructions on their [website](https://www.postgresql.org/download/windows/).

After installing Laravel and Postgresql on your device, clone this repository. ["laravel-rest" folder](https://github.com/MrAghyad/RestaurantTableReservation/tree/main/laravel-rest) contains the project files that we need to work with. Hence, get into the project folder and follow these steps:

1. If you have installed Postgresql with the default settings, then run the database either through command-line or via pgadmin4 and setup your database.
2. Copy .env.example and rename it to .env.
3. Run the following command (after opening cmd in the project's folder) to generate APP_KEY
	* `> php artisan key:generate` 
4. Run the following command (after opening cmd in the project's folder) to generate JWT_SECRET
	* `> php artisan jwt:secret`
5. Update .env file in the project folder with your database information as follows:

| Key			| Value 				 |
|-----------------------|--------------------------------------- |
|DB_CONNECTION		|pgsql					 |
|DB_HOST		|(server host, default = 127.0.0.1)	 |
|DB_PORT		|(server port, default = 5432)	 	 |
|DB_DATABASE		|(database name)			 |
|DB_USERNAME		|(database ussername, default = postgres)|
|DB_PASSWORD		|(database password)			 |

6. After setting up .env file, Run the following command to establish database migrations
	* `> php artisan migrate`
7. Run this command to seed the database with some initial users
	* `> php artisan db:seed --class=UserSeeder`
	* The details of the created users credentials can be found in the [UserSeeder class](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/database/seeders/UserSeeder.php)
8. Run the following command to start Laravel's local development server using the Artisan CLI's serve command (make sure you are inside the project folder)
	* `> php artisan serve --port=8080`

**CONGRATULATIONS**🎉 the API is now up and running on your local Windows 10 machine.

-----------

#### 2. Docker
-----------
The project file ["laravel-rest" folder](https://github.com/MrAghyad/RestaurantTableReservation/tree/main/laravel-rest) contains files that allow composing and dockerizing the project into a docker container, where the project contains .docker folder and docker-compose.yml. However, before running docker commands ensure that you have [docker installed](https://docs.docker.com/desktop/) on your device. To compose a docker container from the docker files in the project folder, open the command line in the project folder, and run the following command to build, run, and containerize the docker images in the project (All steps in Windows 10 build were automated! and can be found inside .docker folder and in docker-compose.yml).
	* `> docker-compose up --build -d`


**CONGRATULATIONS**🎉 the API is now up and running on Docker.


### Testing
-----------
The project has been tested with integration tests and API tests.

--------------------------------

#### 1. Feature/Integration Testing
--------------------------------
To test the project PHPUnit was utilized to build integration tests, as PHPUnit is mainly used for Laravel testing. The testing environment was separated from the development environment, where .env.testing was used to define environment variables along with using phpunit.xml that defines the environment details to be used in the tests. Therefore, we used an in memory defined sqlite database for the purpose of testing away from the postgresql database used for development and production.

To run the tests all you have to do is to fire this command in the project folder:
* `> php artisan test`

----------------

#### 2. API Testing
----------------
The project's APIs were also tested using Postman. The collection and environment variables files to setup your postman workspace are available in the [postman folder](https://github.com/MrAghyad/RestaurantTableReservation/tree/main/postman) in this repository. 

The environment variables file contains three vars, the url, adminToken, and employeeToken. The tokens are updated automatically whenever you request the login API. 
