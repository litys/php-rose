# PHP Rose
Micro **REST API** framework written in pure PHP for micro and small projects. Create rest api blazing fast with JWT authentication.

You dont have time for learning backend but you know how do things in PHP?
Or maybe you wanna learn how REST API works in PHP?
**PHP Rose** is for small project and for some, to learn how it works. 

___

1. [Instalation](#Instalation)
2. [How it works](#How-it-works)
3. [Permissions](#Permissions)
4. [Functions](#Functions)
5. [Fast configuration](#Fast-configuration)
6. [What you can touch](#What-you-can-touch)

___

## Installation
1. Clone repo and run *composer install*.
2. Go to: `/config/database.php` and edit your connection with database
3. Run `php -S localhost:8000`
4. Send **GET** Request at `localhost:8000\setup`

That last point will create table for users (required for authorization).
Now you have working REST API backend with simple functions. 
Read small documentation bellow to understand how to use **PHP Rose**.

## How it works
First of all, that framework aiming for **simplicity**.
You can use only **GET** and **POST**. 
- **GET** - For returning general information, without user interactions and requests
- **POST** - For messing with backend, returning specific informations (eg. about user)

### Where add routes?
In file `router.php` in home folder. You can use `get()` and `post()` functions.
Firsth parameter is your route for request.
Second parameter is your controller which will run after visit that route.
Third parameter is not required, it's [permission](##-Permissions) parameter. If not given, route will be allowed to visit for guests (authorization for that route will be disabled). That effect can be achieved with sending 0 as third parameter.

For code clarity controllers are held in folder `/controllers`. Just *include_once* group of controllers to `router.php`.
#### Example:
`get('/example_route','example_controller',1);` __
Sending request at `localhost:8000/example_route` will trigger function `example_controller` and that route will be available only for users with permission `1`.
`post('/second_example_route','second_example_controller');` __
Sending request at `localhost:8000/second_example_route` will trigger function `second_example_controller` and that route will be available for all users (*have permission 0*).

### Where add controllers
In folder `controllers`. You can create how much you want and organize however you want. In file (group of controllers eg. `controllers/general.php`) you can have how much controlers you want.
**Controllers are just a functions**.
#### Example:
```php
function example_controller(){
    response([
        'message'=>'This is example response'
    ],200);
};
```
Controller will return JSON response with status code 200. [Read more about functions in PHP Rose](##-Functions).

## Permissions
Before entering to controller user is verified. If route required permission user token is checked ([go to fast configuration to configure permissions](##-Fast-configuration) but firsth read that section). Permissions are realy simple, they are just numbers from 0.

Recommended way to look at this:
- 0 - is for guest, all users having link will have permission to view that route
- 1 - is for admin
- 2 - is for user
- 3 - is for eg. smaller user

User with permission **2** can view all routes for permission **2,3,4,5** etc. But can't view route with permission **1**. The same applies to user with permission **3**, user can't view routes with permission **1** and **2** but can view **3,4,5** etc. Routes with permission 0 are available for all permissions and guest (not logged users, without token)

**Token is sending in Headers**. Set header name at **Token**.

## Functions
`response()` - For fastest creating responses. At firsth parameter insert array (later will be processed to JSON) and at second parameter insert response status code (*not required, not given will return status code 200*).
```php
response([
        'message'=>'This is example response'
    ],200);
```

`return_404()` - For custom 404. It's not required function. It's overwrites **PHP Rose** default 404. Use only once, recommended it's to put this function in `controllers/general.php` 

``DB::query()`` - For requests to database. In firsth parameter just type SQL query. Second argument is optional and handle params. Examples can be found in `/controllers/general.php` but in general use something like:
```DB::query("SELECT * FROM users")``` or ```DB::query("SELECT * FROM users WHERE id=:id",['id'=>$id])```

## Fast configuration
1. Go to `system/auth.php` and edit **secret_key** for JWT authorization. You can edit **token_exp** if you wanna choose how long token will be valid.
2. Send request at `localhost:8000/register` and create firsth user (use **POST** request with login(*text*),password(*text*) and permission(*int*))
3. Send request at `localhost:8000/login` and login your user (use **POST** request with login(*text*) and password(*text*))
4. Set recived token in headers and for header name give **Token**

That's all. Now you can delete examples in code and setup function (controller can be found in `/controllers/general.php` and route in `router.php`)

## What you can touch
### Folders (hierarchy)
- **config** - config files, you can add there important things
- **controllers** - controllers for routes, you can add how much you want and organize however you want
- **system** - better do not touch if not necessary
### Files
- **index.php** - be careful with editing
- **router.php** - feel free, here you create routes
### Other files (just information)
- **system/router.php** - routing heart, handling requests
- **system/auth.php** - authentication heart, handling permissions
- **controllers/general.php** - general controller for general controllers
- **controllers/users.php** - controller for authorization (register new user and login)
- **config/database.php** - database connection