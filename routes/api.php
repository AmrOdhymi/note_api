<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\AuthController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {
    
    // Notes
    Route::resource('notes', NoteController::class)->only(['index','store','update','destroy']);
    //archive
    Route::patch('/notes/{id}/toggleArchive', [NoteController::class, 'toggleArchive']);

    //tags
    Route::get('/tags', function (Request $request) {
        return response()->json($request->user()->tags()->select('id', 'name')->get());
    });

    //auth
    Route::post('/logout',[AuthController::class,'logout']);
});
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
