<?php

use App\Models\Book;
use App\Models\Library;
use App\Models\User;
use Illuminate\Support\Arr;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->seed();
    Sanctum::actingAs(User::where('email', 'test@example.com')->first()); // as test user
});

test('a user can get a list of libraries', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get('/api/libraries');

    $response->assertStatus(200);

    $json = $response->json();
    $response->assertSee(['total' => 25]);
    $response->assertSee(['per_page' => 10]);
    $this->assertCount(10, $json['data']);
});

test('a user can get a list of a library\'s books', function () {

    $library = Library::inRandomOrder()->with('books')->first();

    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get("/api/libraries/{$library->id}/books");

    $response->assertStatus(200);

    $json = $response->json();
    $booksCount = $library->books->count();
    $response->assertSee(['total' => $booksCount]);
    $response->assertSee(['per_page' => 10]);
    $this->assertCount($booksCount > 10 ? 10 : $booksCount, $json['data']);
});


test('a user can get their profile', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    $user = User::where('email', 'test@example.com')->withCount('likes')->first();
    $response = $this->get('api/user');
    $response->assertStatus(200);
    $response->assertJson($user->toArray());
});

test('a user can get library details', function () {
    $library = Library::inRandomOrder()->first();
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get("/api/libraries/{$library->id}");
    $response->assertStatus(200);
    $response->assertJson($library->toArray());
});

test('a user can get books list', function () {
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get('/api/books');
    $response->assertStatus(200);
    $response->assertSee(['total' => 200]);
    $response->assertSee(['per_page' => 10]);
    $json = $response->json();
    $this->assertCount(10, $json['data']);
});

test('a user can search through books list', function () {
    $book1 = Book::factory()->create([
        'title' => 'A story about the fox'
    ]);
    $book2 = Book::factory()->create([
        'description' => 'The quick brown fox jumps over the lazy dog'
    ]);
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get('/api/books?search=fox');
    $response->assertStatus(200);
    $response->assertSee(['total' => 2]);
    $response->assertSee(['per_page' => 10]);
    $response->assertSee(Arr::except($book1->toArray(), 'likers'));
    $response->assertSee(Arr::except($book2->toArray(), 'likers'));
});

test('a user can get book details', function () {
    $book = Book::inRandomOrder()->with('libraries')->first();
    /** @var \Illuminate\Testing\TestResponse $response */
    $response = $this->get("/api/books/{$book->id}");
    $response->assertStatus(200);
    $response->assertJson($book->toArray());
});

test('a user can create new books', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    $response = $this->postJson('api/books', []);
    $response->assertStatus(422);
    $response->assertJsonValidationErrorFor('title');
    $response->assertJsonValidationErrorFor('author');

    $book = Book::factory()->make();
    $response = $this->postJson('api/books', $book->toArray());
    $response->assertStatus(201);
    $response->assertJson($book->toArray());

    $this->assertDatabaseHas('books', ['title' => $book->title]);

});


test('a user can update existing books', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    $book = Book::factory()->create();

    $response = $this->putJson("api/books/{$book->id}", []);
    $response->assertStatus(422);
    $response->assertJsonValidationErrorFor('title');
    $response->assertJsonValidationErrorFor('author');

    $data = Book::factory()->make();
    $response = $this->putJson("api/books/{$book->id}", $data->toArray());
    $response->assertStatus(200);
    $response->assertJson($data->toArray());
});


test('a user can delete a book', function () {
    /** @var \Illuminate\Testing\TestResponse $response */

    $book = Book::factory()->create();
    $response = $this->delete("api/books/{$book->id}");
    $response->assertStatus(204);

    $this->assertDatabaseMissing('books', ['id' => $book->id]);
});
