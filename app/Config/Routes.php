<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->group('game', ['filter' => 'isLogged'], static function ($routes) {
    $routes->group('character', static function ($routes) {
        $routes->post('select', 'Character::select');
        $routes->get('list', 'Character::list');
        $routes->get('create', 'Character::createView');
        $routes->post('create', 'Character::create');
        $routes->delete('delete', 'Character::delete');
    });
    $routes->get('lobby', 'Main::index');
    $routes->get('/', 'Main::index');
});

$routes->group('user', ['filter' => 'isLogged'], static function ($routes) {
    $routes->group('admin', ['filter' => 'isAdmin'], static function ($routes) {
        $routes->get('manage', 'Admin::manage');
        $routes->group('users', static function ($routes) {
            $routes->get('manage', 'UserAdmin::manage');

        });
        $routes->group('classes', static function ($routes) {
            $routes->get('manage', 'ClassAdminController::manage');
            $routes->post('create', 'ClassAdminController::create');
            $routes->get('edit/(:num)', 'ClassAdminController::updater/$1');
            $routes->put('update', 'ClassAdminController::create');
            $routes->delete('delete', 'ClassAdminController::delete');
        });
        $routes->group('announcements', static function ($routes) {
            $routes->get('manage', 'AnnouncementAdminController::manage');
            $routes->post('create', 'AnnouncementAdminController::create');
            $routes->get('edit/(:num)', 'AnnouncementAdminController::updater/$1');
            $routes->put('update', 'AnnouncementAdminController::create');
            $routes->delete('delete', 'AnnouncementAdminController::delete');
        });
        $routes->group('tags', static function ($routes) {
            $routes->get('manage', 'TagsAdmin::manage');

        });
    });
    $routes->get('manage', 'User::manage');
    $routes->put('update', 'User::update');
});

$routes->get('/', 'Home::index');
$routes->get('home', 'Home::index');
$routes->get('how-to-play', 'Home::how-to-play');
$routes->get('classes', 'ClassController::index');
$routes->get('classes/(:any)', 'ClassController::get/$1');
$routes->get('announcements/(:segments)/(:segments)', 'Announcement::index/?1/?2');


$routes->get('login', 'Session::login');
$routes->post('login', 'Session::logining');
$routes->get('register', 'Session::register');
$routes->post('register', 'Session::registering');
$routes->get('logout', 'Session::logout');

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
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
