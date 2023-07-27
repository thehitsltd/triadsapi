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
        Schema::create('videos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('poster_id');
            $table->text('description');
            $table->text('src');
            $table->text('hashtags');
            $table->text('categories');
            $table->text('mentions');
            $table->float('poster_popularity_index');
            $table->float('poster_video_priority');
            $table->float('video_manual_boost_constant');
            $table->float('video_popularity');

            $table->foreign('poster_id')->on('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
