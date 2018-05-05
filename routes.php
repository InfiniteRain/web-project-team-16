<?php

use WebTech\Hospital\Router;

Router::registerGet('/^\/$/', 'HomeController@pageHome');
Router::registerGet('/^\/contact$/', 'HomeController@pageContact');
Router::registerGet('/^\/about$/', 'HomeController@pageAbout');

Router::registerGet('/^\/login$/', 'LoginController@pageLogin');
Router::registerPost('/^\/login$/', 'LoginController@login');
Router::registerGet('/^\/logout$/', 'LoginController@logout');
Router::registerGet('/^\/register$/', 'LoginController@pageRegister');
Router::registerPost('/^\/register$/', 'LoginController@register');
