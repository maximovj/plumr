<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();

            // Llave foranea para el que sigue
            $table->foreignId('follower_id')
            ->constrained('users', 'id')
            ->onDelete('cascade');

            // Llave foranea para al que siguen
            $table->foreignId('following_id')
            ->constrained('users', 'id')
            ->onDelete('cascade');

            $table->unique(['follower_id', 'following_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
}
