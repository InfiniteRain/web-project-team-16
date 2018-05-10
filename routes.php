<?php

use WebTech\Hospital\Router;

Router::registerGet('/^\/$/', 'HomeController@pageHome');
Router::registerGet('/^\/contact$/', 'HomeController@pageContact');
Router::registerPost('/^\/contact$/', 'HomeController@contact');
Router::registerGet('/^\/about$/', 'HomeController@pageAbout');

Router::registerGet('/^\/login$/', 'LoginController@pageLogin');
Router::registerPost('/^\/login$/', 'LoginController@login');
Router::registerGet('/^\/logout$/', 'LoginController@logout');
Router::registerGet('/^\/register$/', 'LoginController@pageRegister');
Router::registerPost('/^\/register$/', 'LoginController@register');
Router::registerGet('/^\/forgot$/', 'LoginController@pageForgot');
Router::registerPost('/^\/forgot$/', 'LoginController@forgot');
Router::registerGet('/^\/forgot\/([a-zA-Z0-9]+)$/', 'LoginController@pageRecover');
Router::registerPost('/^\/forgot\/([a-zA-Z0-9]+)$/', 'LoginController@recover');

Router::registerGet('/^\/profile$/', 'ProfileController@pageProfile');
Router::registerPost('/^\/profile$/', 'ProfileController@editProfile');
Router::registerGet('/^\/users$/', 'ProfileController@pageUsers');
Router::registerGet('/^\/users\/(\d+)$/', 'ProfileController@pageAdminProfile');
Router::registerPost('/^\/users\/(\d+)$/', 'ProfileController@editAdminProfile');

Router::registerGet('/^\/appointments\/book$/', 'AppointmentController@pageBookAppointment');
Router::registerPost('/^\/appointments\/book$/', 'AppointmentController@bookAppointment');
Router::registerGet('/^\/appointments\/view$/', 'AppointmentController@pageViewAppointments');

Router::registerGet('/^\/appointments\/(\d+)\/approve$/', 'AppointmentController@approve');
Router::registerGet('/^\/appointments\/(\d+)\/decline$/', 'AppointmentController@decline');
Router::registerGet('/^\/appointments\/(\d+)\/edit$/', 'AppointmentController@pageEdit');
Router::registerPost('/^\/appointments\/(\d+)\/edit$/', 'AppointmentController@edit');
Router::registerGet('/^\/appointments\/(\d+)\/cancel$/', 'AppointmentController@cancel');
