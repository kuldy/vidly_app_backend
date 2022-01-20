<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// $routes->get('/', 'Home::index');
// $routes->resource('product');
$routes->resource('employees');
$routes->resource('products');

$routes->group("api", function ($routes) {

    $routes->post("register", "User::register");
    $routes->post("login", "User::login");
    $routes->get("profile", "User::details");
    $routes->get("logout", "User::logout");
    
    $routes->get("products", "Products::index");
    $routes->post("add_product", "Products::addProduct");

    $routes->get("listings", "Listings::listings", ['filter' => 'auth']);
    $routes->post("add-listing", "Listings::addListing");
    $routes->get("images", "Images::index");

    // $routes->get("movies", "Movies::movies", ['filter' => 'auth']);
    // $routes->post("add-movie", "Movies::addMovie");
    // $routes->get('view-movie/(:num)', 'Movies::viewMovie/$1', ['filter' => 'auth']);
    // $routes->put('update-movie/(:num)', 'Movies::updateMovie/$1');
    // $routes->delete('delete-movie/(:num)', 'Movies::deleteMovie/$1');

    $routes->get("movies", "Movies::index");
    $routes->post("movies", "Movies::create");
    $routes->get('movies/(:num)', 'Movies::show/$1');
    $routes->put('movies/(:num)', 'Movies::update/$1');
    $routes->delete('movies/(:num)', 'Movies::delete/$1');

    $routes->resource('photos');
    // Equivalent to the following:
    // $routes->get('photos/new',             'Photos::new');
    // $routes->post('photos',                'Photos::create');
    // $routes->get('photos',                 'Photos::index');
    // $routes->get('photos/(:segment)',      'Photos::show/$1');
    // $routes->get('photos/(:segment)/edit', 'Photos::edit/$1');
    // $routes->put('photos/(:segment)',      'Photos::update/$1');
    // $routes->patch('photos/(:segment)',    'Photos::update/$1');
    // $routes->delete('photos/(:segment)',   'Photos::delete/$1');


    $routes->get("bugs", "Bugs::index");
    $routes->post("bugs", "Bugs::create");
    $routes->get('bugs/(:num)', 'Bugs::show/$1');
    $routes->put('bugs/(:num)', 'Bugs::update/$1');
    $routes->delete('bugs/(:num)', 'Bugs::delete/$1');
    
    $routes->put('bugs-resolve/(:num)', 'Bugs::resolveBug/$1');
    $routes->put('bugs-assign/(:num)', 'Bugs::assignBug/$1');




    $routes->get("genres", "Genres::genres");
    $routes->post("add-genre", "Genres::addGenre");
    // $routes->get("get-genre/(:num)", "Genres::getGenre/$1");
    $routes->get('view/(:num)', 'Genres::getGenre/$1');
    
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
