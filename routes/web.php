<?php

use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});
Route::get('/attributionStand.php', function () {
    return view('attributionStand');
});

Route::get('/listeGroupe.php', function () {
    return view('listeGroupe');
});





Route::get('/consultationAttributions.php', function () {
    return view('consultationAttributions');
});

Route::get('/detailEtablissement.php', function () {
    return view('detailEtablissement');
});

Route::get('/detailGroupe.php', function () {
    return view('detailGroupe');
});

Route::get('/listeEtablissements.php', function () {
    return view('listeEtablissements');
});

Route::get('/listeStand.php', function () {
    return view('listeStand');
});

Route::get('/detailGroupe.php', function () {
    return view('detailGroupe');
});

Route::get('/résultatGroupe.php', function () {
    return view('résultatGroupe');
});

Route::match(['get', 'post'],'/baseModifie.php', function () {
    return view('baseModifie');
});

Route::match(['get', 'post'],'/creationGroupe.php', function () {
    return view('creationGroupe');
});

Route::match(['get', 'post'],'/creationEtablissement.php', function () {
    return view('creationEtablissement');
});

Route::match(['get', 'post'],'/attributionStand.php', function () {
    return view('attributionStand');
});




