<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'AuthController::login');
$routes->match(['get', 'post'], 'login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');

    // Staff dan Admin boleh akses barang masuk, stok, barcode, dan scan
    $routes->get('stock', 'StockController::index');
    $routes->get('stock/create', 'StockController::create');
    $routes->post('stock/store', 'StockController::store');
    $routes->get('stock/update/(:num)', 'StockController::editStock/$1');
    $routes->post('stock/save-stock/(:num)', 'StockController::saveStock/$1');
    $routes->post('stock/delete/(:num)', 'StockController::delete/$1');

    $routes->get('barcode/print/(:num)', 'BarcodeController::print/$1');
    $routes->get('barcode/print_product/(:num)', 'BarcodeController::print_product/$1');

    $routes->get('scan', 'ScanController::index');
    $routes->post('scan/check', 'ScanController::check');

    // Modul Penjualan
    $routes->get('sales', 'SalesController::create');
    $routes->post('sales/scanBarcode', 'SalesController::scanBarcode');
    $routes->post('sales/store', 'SalesController::store');

    // Khusus Admin
    $routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('products', 'ProductController::index');
        $routes->get('products/create', 'ProductController::create');
        $routes->post('products/store', 'ProductController::store');
        $routes->get('products/edit/(:num)', 'ProductController::edit/$1');
        $routes->post('products/update/(:num)', 'ProductController::update/$1');
        $routes->post('products/delete/(:num)', 'ProductController::delete/$1');

        $routes->get('staff', 'StaffController::index');
        $routes->get('staff/create', 'StaffController::create');
        $routes->post('staff/store', 'StaffController::store');
        $routes->get('staff/edit/(:num)', 'StaffController::edit/$1');
        $routes->post('staff/update/(:num)', 'StaffController::update/$1');
        $routes->post('staff/delete/(:num)', 'StaffController::delete/$1');

        $routes->get('reports', 'ReportController::index');
        $routes->get('reports/export-pdf', 'ReportController::exportPdf');

        // Admin Sales Reports
        $routes->get('sales', 'SalesController::index');
        $routes->get('sales/show/(:num)', 'SalesController::show/$1');
    });
});