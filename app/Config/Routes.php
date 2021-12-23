<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

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
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->group('api', ['namespace' => 'App\Controllers\Api'], function ($route) {

    /*
     * SISWA
     */

    // login
    $route->post('login/siswa', 'login::siswa');

    // register
    $route->post('register/siswa', 'register::siswa');

    // pkl
    $route->get('pkl', 'pkl::index');
    $route->get('pkl/detail/(:num)', 'pkl::detail/$1'); //id pkl
    $route->post('pkl/tambah', 'pkl::tambah');
    $route->post('pkl/gambar/(:num)', 'pkl::upload_gambar/$1'); //id pkl

    // pendaftaran
    $route->post('pendaftaran', 'pendaftaran::create');
    $route->get('pendaftaran/detail/(:num)', 'pendaftaran::show/$1'); //id siswa

    // harian
    $route->get('harian/(:num)', 'harian::show/$1'); //id siswa
    $route->post('harian/tambah', 'harian::create'); //modif

    /*
     * GURU
     */

    // penyerahan
    $route->get('penyerahan/guru/(:num)/(:num)', 'penyerahan::index_get/$1/$2'); //id guru , kode status
    $route->post('penyerahan/status/id/(:num)/kode/(:num)', 'penyerahan::status_post/$1/$2'); //id pendaftaran, kode status
    $route->post('penyerahan/gambar/(:num)', 'penyerahan::upload_gambar/$1'); //id pendaftaran

    // penarikan
    $route->get('penarikan/guru/(:num)/(:num)', 'penarikan::index_get/$1/$2'); //id guru , kode status
    $route->post('penarikan/status/id/(:num)/kode/(:num)', 'penarikan::status_post/$1/$2'); //id pendaftaran, kode status
    $route->post('penarikan/gambar/(:num)', 'penarikan/upload_gambar/$1');

    // monitoring
    $route->post('monitoring/(:num)', 'monitoring::index_post/$1'); //id pendaftaran
    $route->get('monitoring/guru/(:num)/(:num)', 'monitoring::pkl/$1/$2'); //id guru , kode status
    $route->get('monitoring/(:num)', 'monitoring::index_get/$1'); //id pendaftaran
    $route->post('monitoring/status/id/(:num)/kode/(:num)', 'monitoring::status/$1/$2'); //id pendaftaran , kode status
    $route->post('monitoring/gambar/(:num)', 'monitoring::upload_gambar/$1'); //id monitoring

    // login
    $route->post('login/guru', 'login::guru');

    // register
    $route->post('register/guru', 'register::guru');

    // pkl
    $route->get('pkl/status/(:num)', 'pkl::status/$1'); //kode status
    $route->post('pkl/status/id/(:num)/kode/(:num)', 'pkl::status_post/$1/$2'); //id pkl , kode status
    $route->patch('pkl/ubah/id/(:num)', 'pkl::ubah/$1'); //id pkl
    $route->get('pkl/pembimbing/(:num)/(:num)', 'pkl::pilih_pembimbing/$1/$2'); //id pkl , id guru

    /*
     * ADMIN
     */

    // admin
    $route->post('login/admin', 'login::admin');

    // guru
    $route->get('guru', 'guru::index');

    // siswa
    $route->get('siswa', 'siswa::index');

    //jarak
    $route->get('jarak/pkl', 'jarak::jarak_pkl'); //modif
    $route->get('jarak/pkl/guru/(:num)', 'jarak::jarak_pkl_limit/$1'); //modif
    // $route->get('jarak/(:any)/(:any)/(:any)/(:any)', 'jarak::jarak/$1/$2/$3/$4');

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
