<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
 Route::get('/', function () {
     return '<h1>Api per il progetto finale del corso di Fullstack Developer</h1>
     <ul>
     <li>/register per registrarsi</li>
     <li>/login per il login</li>
     <li>/expenses per ottenere le spese dell utente loggato</li>
     <li>/expenses/{id} per ottenere una spesa</li>
     <li>/expenses/search/{title} per filtrare le spese tramite nome</li>
     <li>/expenses/total per ottenere la spesa totale</li>
     <li>/expenses?filter[month]= per filtrare le spese in base al mese</li>
     <li>/expenses (POST) per creare nuove spese</li>
     <li>/expenses/{id} (PUT) per modificare la spesa</li>
     <li>/expenses/{id} (DELETE) per eliminare una spesa</li>
     <li>/logout per fare il logout</li>
     </ul>
     '
     ;
 });
