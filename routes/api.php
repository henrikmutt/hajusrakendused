<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FightCardController;

Route::get('/fight-cards/{uuid}', [FightCardController::class, 'apiByUuid']);

