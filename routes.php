<?php

use WebTech\Hospital\Router;

Router::registerGet('/^\/$/', 'HomeController@getHomePage');

Router::registerPost('/^\/login$/', 'LoginController@login');
Router::registerGet('/^\/logout$/', 'LoginController@logout');
Router::registerPost('/^\/register$/', 'LoginController@register');


