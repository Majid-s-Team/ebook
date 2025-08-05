<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_category_id')->constrained()->onDelete('cascade');
            $table->string('book_name');
            $table->text('about')->nullable();
            $table->string('author_name');
            $table->boolean('is_popular')->default(false);
            $table->boolean('is_audio')->default(false);
            $table->boolean('is_reader')->default(false);
            $table->boolean('made_into_movie')->default(false);
            $table->decimal('price', 10, 2)->default(0);
            $table->integer('quantity')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
