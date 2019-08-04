# web-project-team-16

Web project of team 16, ICD0007

## Disclaimer

This was a university assignment that me and my team had to work on. The back-end, almost in it's entirety, was written by me. My groupmates were mostly responsible for the front-end stuff, so I did not touch it at all. The structure of the back-end was designed and implemented by me. The the rest of this README served as a quick overview of the back-end's design for my group mates, because we had to defend the work. I'm making this repository public so that if anybody wants to see what we've done, they can.

---

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

_NOT TO BE CONFUSED WITH BOOTSTRAP CSS LIBRARY_

This folder is used for storing various files for bootstrapping the application. Bootstrapping is basically a set of definite actions (scripts) that are required to be run at the very beginning of the loading process of a web page, otherwise the website will not function properly.

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

Both of these methods accept the same type of arguments. The first argument is the regex (regular expression) string that is used to match the current route with. For example, `/^\/admin$/` will match the route `/admin`, and `/^\/admin\/users\/(\d+)$/` will match the route `/admin/users/<int>` (where `<int>` represents any integer. For example, `/admin/users/10` would match with that regex). The second argument is the controller handle, matching the following format: `<ControllerClassName>@<ControllerClassMethod>`, where `<ControllerClassName>` part is replaced with the name of the controller class, and `<ControllerClassMethod>` is replaced with the method of that class that this route shall invoke. If you don't know what a controller is, don't worry, next section is going to cover that.

Final example: `Router::registerGet('/^\/admin\/users\/(\d+)$/', 'AdminController@getUserPage');`

The example above will register the route `/admin/users/<int>`. Every time this route gets accessed, method `AdminController::getUserPage()` is going to be invoked. Router will pass the request data (either GET or POST) as the first argument, while any captured regex groups will be passed in the following arguments (regex groups are parts of the regex string that are encapsulated in brackets, in our example, that is the `(\d+)` part, it will get passed as the second argument).

To summarize, URL looking like `http://hospital.com/admin/users/10` will invoke `AdminController::getUserPage()` method and pass the GET/POST data as the first argument and the number `10` as the second argument.

## MVC

This project makes use of the MVC paradigm. MVC paradigm is basically a way to structure the backend of a website. MVC consists of three main parts: Models, Views and Controllers.

### Models

Models are objects which are used to communicate with the database and query data from it. The implementation of models in this project is in the form of an abstract class, which gets inherited by the model classes. Model classes can be viewed in the `app/Models` folder.

Long story short, this implementation helps us to ease the work with the database. The database of this project has 4 tables. This project has 4 model classes. Each model class represents a table in the database. For example, class `User` is tied to the table `SYSUSER` in the database.

Let me demonstrate to you how powerful this implementation is. The following code snippet demonstrates how you can access the data from the SYSUSER table from the database with the usage of models, in this case we want to get the data of the user with the ID of 1:

```php
<?php
use WebTech\Hospital\Models\User;

$user = User::find(1);
echo 'First name: ' . $user->first_name . '<br>';
echo 'Last name: ' . $user->last_name . '<br>';
echo 'User type: ' . $user->userType()->name . '<br>';
```

The static method `find` is available to all the model classes. Basically, it looks up the row by the primary key (ID), that is passed as the first argument. If the ID was not found, then an exception is thrown, but if it was found, then that object will get all the row values fetched from the database and turn them into properties of itself, meaning that the data can be accessed as simply as `$user->last_name`.

But that's not all, it's also easy to update the data:

```php
<?php
use WebTech\Hospital\Models\Users;

$user = User::find(1);
$user->first_name = "New first name";
$user->last_name = "New last name";
$user->save();
```

As you can see from the example above, you can change the row values of model by just changing the properties of the model object and then calling the `save` method. That method will check all the changed properties, and change the data in the database accordingly.

Moreover, it's really easy to delete rows as well:

```php
<?php
use WebTech\Hospital\Models\Users;

$user = User::find(1);
$user->delete();
```

As you can clearly see, deleting a row is as easy as calling the `delete` method on the model object.

### Views

Views are the pages that the website visitors are going to be looking at, mostly consisting of HTML, but also containing some of the PHP templating features. What views to render is decided by the controller method that gets invoked by the router.

Controller might pass some data to a view, which then can be used to dynamically display data using the PHP templating features.

More information about it is going to be in the next subsection, but what you need to remember now is that a view is the end point of the website.

### Controllers

If models communicate with the database and views display information to the visitors, then the controllers are the part that connects models and views together.

Controller classes are located at `app/Controllers`. Each of the controller classes have various methods that are invoked by the router based on the current URL (as was explained in the routing section.)

Each method has to return a view which then will be rendered to the visitor. Alongside views, you can pass some special data that you want to later use in the rendered view.

For example, let's say want to have a home page, the route of which is going to be `/home`. In order to achieve that, we have to do a few things:

1.  Add a route to `routes.php`:
    ```php
    <?php
    Router::registerGet('/^\/home$/', 'HomeController@getHomePage');
    ```
2.  Create a controller with the name `HomeController` over at `app/Controllers`:

    ```php
    <?php
    namespace WebTech\Hospital\Controllers;

    use WebTech\Hospital\Controller;

    class HomeController extends Controller
    {

    }
    ```

3.  Create the method for our controller that will render the home page view:

    ```php
    <?php
    namespace WebTech\Hospital\Controllers;

    use WebTech\Hospital\Controller;

    class HomeController extends Controller
    {
        public function getHomePage($request)
        {
            $welcomeMessage = 'Welcome to our website!';

            return $this->view('home', [
                'welcomeMessage' => $welcomeMessage
            ]);
        }
    }
    ```

4.  Create the view:
    ```php
    <!doctype html>
    <head>
        <title>Home page</title>
    </head>
    <body>
        <h1>Home page</h1>
        <h2><?= $welcomeMessage ?></h2>
    </body>
    ```

And that's basically it. As you can see, in order to render a view from a controller, we have to return the result of a method `$this->view`. First argument takes the name of the view. That name will get transformed into a proper path to the file. For example, `home` will load a view file at `views/home.php`. The second argument is the extra data (in the form of an associative array) that we want to later use in our view. In this example, we passed an array that has an element 'welcomeMessage' which stores the welcome message that we later displayed on the view.

In order to access the data that was passed into a view, we need to simply address an element of that array as if it were a simple variable. In our case, all we needed to do is to print out `$welcomeMessage`, and that's it.

## Database

While models do make our lives much easier, there are still some cases where one would want to use raw MySQL queries to construct more complex queries. And that is possible thanks to the `Database` class. This class makes query execution as simple as calling only one static method.

Here's an example:

```php
<?php
Database::query('SELECT * FROM SYSUSER WHERE id = ?', [1]);
```

That's all that's needed. Things such as connectivity and string escaping is dealt with in the background. In order to prevent the MySQL injections, we do NOT concatenate the `WHERE` statement inside of the query string, instead, we use a question mark to mark the spots where we want to inject our values, and pass the values in an array as the second argument in the correct order. That way, everything will be safely escaped.
