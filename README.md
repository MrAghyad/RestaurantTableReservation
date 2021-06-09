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
The API consists of several routets and endpoints. Most of the APIs are secured with JWT-Auth token. The source code of the APIs can be found in [laravel-rest/app/Http/Controllers](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/app/Http/Controllers). Moreover, the routes definition can be found in [laravel-rest/routes/api.php](https://github.com/MrAghyad/RestaurantTableReservation/blob/main/laravel-rest/routes/api.php).

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


<div align="center">
	<img src="https://github.com/MrAghyad/RestaurantTableReservation/blob/main/TableReservationFlow_CheckAvailable.png?raw=true" width="300" height="500" hspace="20"/> <img src="https://github.com/MrAghyad/RestaurantTableReservation/blob/main/TableReservationFlow_ReserveTable.png?raw=true" width="500" height="500" hspace="20"/>
	<br>
	<em>
		Figure: Flow graph of ReservationController's checkAvailable - Figure: Flow graph of ReservationController's store
	</em>
	<br>
	<p>
		(click on the figures to view)
	</p>
</div>


## Usage
Laravel was used to implement this project. The implementation can be found in ["laravel-rest" folder](https://github.com/MrAghyad/RestaurantTableReservation/tree/main/laravel-rest). To be able to fully use the API we discuss in this section details related to setting up the environment, database, and enabling testing.  

### Setting Up The Environment

#### Windows 10

#### Docker

### Setting Up The Database
#### Windows 10

#### Docker

### Testing

#### Feature/Integration Testing

#### API Testing








