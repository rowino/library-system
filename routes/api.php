<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\User;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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


Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
    $user = User::where('email', $request->email)->first();
    if ($user && Hash::check($request->password, $user->password)) {
        return [
            'token' => $user->createToken('main-app')->plainTextToken,
            'user' => $user
        ];
    }
    return response(['error' => 'Invalid email/password'], 401);
});
Route::middleware('auth:sanctum')
     ->group(function () {
         Route:: get('/user', function (Request $request) {
             $user = $request->user();
             $user->loadCount('likes');
             return $user;
         });

         Route::get('/libraries', function (Request $request) {
             return Library::withCount('books')->paginate($request->per_page ?? 10);
         });

         Route::get('/libraries/{library}', function (Request $request, Library $library) {
             return $library;
         });

         Route::get('/libraries/{library}/books', function (Request $request, Library $library) {
             return $library->books()->paginate($request->per_page ?? 10);
         });

         Route::get('/books', function (Request $request) {
             return Book::when($request->search, function (Builder $builder) use ($request) {
                 $builder->where('title', 'like', "%{$request->search}%")
                         ->orWhere('description', 'like', "%{$request->search}%");
             })->paginate($request->per_page ?? 10);
         });

         Route::get('/books/{book}', function (Request $request, Book $book) {
             $book->load('libraries');
             return $book;
         });

         Route::post('/books', function (Request $request) {
             $data = $request->validate([
                 'title' => ['required', 'max:255'],
                 'author' => ['required', 'max:255'],
                 'description' => ['nullable'],
             ]);
             return Book::create($data);
         });

         Route::put('/books/{book}', function (Request $request, Book $book) {
             $data = $request->validate([
                 'title' => ['required', 'max:255'],
                 'author' => ['required', 'max:255'],
                 'description' => ['nullable'],
             ]);
             $book->update($data);
             return $book;
         });

         Route::post('/books/{book}/like', function (Request $request, Book $book) {
             $request->validate([
                 'liked' => ['required', 'bool'],
             ]);

             if ($request->liked) {
                 $book->likers()->syncWithoutDetaching(auth()->id());
             } else {
                 $book->likers()->detach(auth()->id());
             }
             $book->load('likers');
             return $book;
         });

         Route::delete('/books/{book}', function (Request $request, Book $book) {
             $book->delete();
             return response()->noContent();
         });
     });
