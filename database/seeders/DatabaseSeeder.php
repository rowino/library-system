<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Book;
use App\Models\Library;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $users = User::factory(10)->create();
        $users->add($testUser);

        $libraries = Library::factory(25)->create();

        $books = Book::factory(200)->create();

        $libraries->each(function (Library $library) use ($books) {
            $libraryBooks = $books->random(rand(10, 30))
                                  ->keyBy('id')
                                  ->map(fn (Book $book) => ['quantity' => rand(1, 30)]);

            $library->books()->sync($libraryBooks);
        });

        $users->each(function (User $user) use ($books) {
            $user->likes()->sync($books->random(rand(4, 8))->pluck('id'));
        });
    }
}
