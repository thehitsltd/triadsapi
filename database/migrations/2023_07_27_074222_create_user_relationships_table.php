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
        Schema::create('user_relationships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('related_user_id');
            /**
             * 1 == Friendship relationship
             * 2 == Follower relationship
             * 3 == Following relationship
             * 4 == Distant relationship (Default)
             */
            $table->integer('type')->default(4);
            $table->foreign('user_id')->on('users')->references('id');
            $table->foreign('related_user_id')->on('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_relationships');
    }
};
