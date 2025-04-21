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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('podcast_id')->index();
            $table->foreign('podcast_id', 'fk_episodes_podcast_id')
                ->references('id')
                ->on('podcasts')
                ->onDelete('cascade');

            $table->string('title')->index();
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('audio_url');
            $table->string('duration')->nullable();
            $table->boolean('is_featured')->default(false)->index();

            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
