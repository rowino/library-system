<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_books', function (Blueprint $table) {
            $table->foreignId('library_id');
            $table->foreignId('book_id');
            $table->integer('quantity');
            $table->timestamps();
            $table->foreign('library_id')
                  ->references('id')
                  ->on('libraries')
                  ->cascadeOnDelete();
            $table->foreign('book_id')
                  ->references('id')
                  ->on('books')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('library_books');
    }
};
