<?php

use App\Models\Facility;
use App\Models\Region;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $facilities = Facility::with('region')
        ->active()
        ->orderBy('type')
        ->orderBy('name')
        ->take(6)
        ->get();

    return view('welcome', [
        'facilities' => $facilities,
        'uddCount' => Facility::active()->udd()->count(),
        'hospitalCount' => Facility::active()->hospitals()->count(),
        'regionCount' => Region::count(),
    ]);
})->name('home');

/*
|--------------------------------------------------------------------------
| Modular Feature Route Loader
|--------------------------------------------------------------------------
| Each domain feature maintains its own route definition file so team
| members can work independently without route file merge conflicts.
*/
$featureRouteFiles = [
    'auth.php',
    'emergency-requests.php',
    'medical-verifications.php',
    'donor-discovery.php',
    'donor-commitments.php',
    'donation-schedules.php',
    'session-bookings.php',
];

foreach ($featureRouteFiles as $routeFile) {
    $fullPath = __DIR__.'/'.$routeFile;
    if (is_file($fullPath)) {
        require $fullPath;
    }
}
