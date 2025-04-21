<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('cascade')
                ->index(); // index for quicker filtering by category

            $table->string('title')->index(); // indexing for searching/sorting
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->boolean('is_featured')->default(false)->index(); // used for trending filter
            $table->json('platforms')->nullable();

            $table->unsignedBigInteger('play_count')->default(0)->index(); // used for sorting by popularity

            $table->timestamps();

            $table->index('created_at'); // useful for "newly added" sorting
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
