<?php
include_once 'controllers/general.php';
include_once 'controllers/users.php';

get('/','home',0);
get('/users','example_users',2); // Example1 route - controller 'example_users' in general.php
post('/user','example_user',2); // Example2 route - controller 'example_user' in general.php

get('/setup','setup');
post('/login','login');
post('/register','register');