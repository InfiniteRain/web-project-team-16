<?php

use WebTech\Hospital\Router;

Router::registerGet('/^\/$/', 'HomeController@getHomePage');
