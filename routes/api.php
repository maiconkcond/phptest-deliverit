<?php

use App\Http\Controllers\RaceController;
use App\Http\Controllers\RunnerController;
use App\Http\Controllers\CompetitionController;
use App\Models\Competition;
use App\Models\Runner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Inclusão de corredores para uma corrida
Route::post('/corredores', [RunnerController::class, 'create']);
// Inclusão de provas
Route::post('/provas', [RaceController::class, 'create']);
// Inclusão de corredores em provas
Route::post('/competicao', [CompetitionController::class, 'create']);
// Inclusão de resultados dos corredores
Route::post('/competicao/novo', [CompetitionController::class, 'associeateresult']);
// Listagem de classificação das provas gerais
Route::get('/classificacao-geral', [CompetitionController::class, 'getOverallRating']);
// Listagem de classificação das provas por idade
Route::get('/classificacao-por-idade', [CompetitionController::class, 'getAgeRating']);
