<?php

use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    require app_path('Modules/User/Routes/api.php');
    require app_path('Modules/NewsCategory/Routes/api.php');
    require app_path('Modules/News/Routes/api.php');
    require app_path('Modules/Project/Routes/api.php');
    require app_path('Modules/Task/Routes/api.php');
});
