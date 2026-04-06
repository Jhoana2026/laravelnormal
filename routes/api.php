<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;

//Ruta para SELECT
Route::get('/estudiantes', [EstudianteController::class, 'selectEst']); 
Route::get('/est', [EstudianteController::class, 'index']); 
//Ruta para INSERT
Route::post('/estudiantes', [EstudianteController::class, 'store']); 

//Ruta para UPDATE
//Route::put('/est', [EstudianteController::class, 'update']); 
Route::put('/estudiantes/{cedula}', [EstudianteController::class, 'update1']);
//Ruta para DELETE
Route::delete('/estudiantes/{cedula}', [EstudianteController::class, 'destroy']);
