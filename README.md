# web-project-team-16
Web project of team 16, ICD0007

## Project structure overview

This section is a quick summary for the base project structure. More in depth information will start in the next section.

### `config.ini`

This file is used for storing the configuration values which are then loaded into a constant `CONFIG`. The initialization of this constant happens during the bootstrapping process.

### `routes.php`

This file is used to register routes for this website.

### `app` folder

This is where most of the back-end implementation is located, including core classes like `Database`, `Session`, `Router`, etc.. There are separate folders for controllers and models, called `Controllers` and `Models` respectively.

### `public` folder

This folder is the document root of the website. Meaning that everything outside of this folder will not be accessible to the client (visitor of the website).

This folder contains the `.htaccess` file with proper settings for ModRewrite, which are essential for routing to work (more on routing in later sections).

The main PHP file `index.php` is also located here, which if you look into it, you will see that it loads all the bootstrapping, initializes the router and handles exceptions.

### `views` folder

This folder contains views of the website. Basically, the pages that users are going to see.

### `bootstrap` folder

This folder is used for storing various files for bootstrapping the application. Bootstrapping is basically a set of definite actions (scripts) that are required to be run at the very beginning of the loading process of the website, otherwise the website will not function properly.

For example, `autoload.php` initializes the autoloader for our project. Autoloader makes it so that the classes are automatically loaded without the need of doing something like `require_once 'app/Database.php'` each time we need to use this class in a file. Instead, we use the `namespace` and `use` keywords for defining the namespace of the file and loading external classes respectively.

Please, read the following article and make sure that you understand the concept of autoloaders in PHP: http://php.net/manual/en/language.oop5.autoload.php

In this project, I used PSR-4 autoloading standard, make sure that you read that one as well: https://www.php-fig.org/psr/psr-4/

File `app.php` is used for initialization of CONFIG and the start of the PHP session.

File `helpers.php` is a place where all the helper functions are defined (more on those in later sections.)

## Routing

Routing is one of the most essential parts of this project. In short, router allows for the URL's used for this website to look cleaner. Let's say that we want to create a page for admins to view the settings of the users. If we used traditional methods, the URL would look something along the lines of `http://hospital.com/admin.php?action=userSettings&userId=10`, in this example, `action` defines what part of the admin panel to render (user profile in this case), and `userId` defines which user profile to view (profile of the user with ID 10, in this example). This method looks ugly in my opinion. With new routing functionality implemented, we can make the URL look much cleaner and easier to understand: `http://hospital.com/admin/users/10`.

The part of the URL `/admin/users/10` is called a `route`. A route leading to the correct page, one might say. Let's break it up logically: `admin` means the section of the website that is accessible only to admins; `users` means the subsection where admins can view the profiles of users; `10` signifies the ID of the user.

In the old way of doing things, all the different URL's would probably point to different files (`admin.php`, `users.php`, ... etc.), but with the current implementation of the router, requests to all the URL's that do not contain an explicit path to a file will get redirected to `index.php`, where the router will decide, based on the URL, what page the user wants, and according to that, render the correct view.

### Route registration

As was previously stated, routes are defined in the file `routes.php`. If you take a look into this file, you will see that two different methods are used for route registration. Namely, `Router::registerGet` and `Router::registerPost`. As you might have already guessed, method `Router::registerGet` is used to register a route for HTTP method GET, while `Router::registerPost` is used to register a route for HTTP method POST.

Both of these methods accept the same type of arguments. The first argument is the regex string that is used to match the current route with. For example, `/^\/admin$/` will match the route `/admin`, and `/^\/admin\/users\/(\d+)$/` will match the route `/admin/users/<int>` (where `<int>` represents any integer. For example, `/admin/users/10` would match with that regex). The second argument is the controller handle, matching the following format: "<ControllerClassName>@<ControllerClassMethod>", where `<ControllerClassName>` part is replaced with the name of the controller class, and `<ControllerClassMethod>` is replaced with the method of that class that this route shall invoke. If you don't know what a controller is, don't worry, next section is going to cover that. 

Final example: `Router::registerGet('/^\/admin\/users\/(\d+)$/', 'AdminController@getUserPage');`

The example above will register the route `/admin/users/<int>`. Every time this route gets accessed, method `AdminController::getUserPage()` is going to be invoked. Router will pass the request data (either GET or POST) as the first argument, while any captured regex groups will be passed in the following arguments (regex groups are parts of the regex string that are encapsulated in brackets, in our example, that is the `(\d+)` part, it will get passed as the second argument).

To summarize, URL looking like `http://hospital.com/admin/users/10` will invoke `AdminController::getUserPage()` method and pass the GET/POST data as the first argument and the number `10` as the second argument.

