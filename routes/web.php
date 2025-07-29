<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
     

foreach(glob(__DIR__ . "/admin/*.php") as $authRoute){
    require $authRoute;
}

foreach(glob(__DIR__ . "/user/*.php") as $authRoute){
    require $authRoute;
}

foreach(glob(__DIR__ . "/device/*.php") as $authRoute){
    require $authRoute;
}

foreach(glob(__DIR__ . "/auth/*.php") as $authRoute){
    require $authRoute;
}