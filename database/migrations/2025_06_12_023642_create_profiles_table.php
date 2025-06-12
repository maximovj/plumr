<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // Llave foránea al usuario
            $table->foreignId('user_id')
            ->constrained('users', 'id')
            ->cascadeOnDelete();

            $table->date('birthday'); // Obligatorio
            $table->string('fullname'); // Obligatorio
            $table->string('sex'); // Obligatorio

            $table->string('number_phone')->nullable(); // Optional
            $table->string('cover')->nullable(); // Optional
            $table->string('photo')->nullable(); // Optional
            $table->string('country')->nullable(); // Optional
            $table->string('address')->nullable(); // Optional
            $table->string('city')->nullable(); // Optional
            $table->string('status')->nullable(); // Optional
            $table->string('bio', 160)->nullable(); // Optional
            $table->json('network_social')->nullable(); // Optional
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
        Schema::dropIfExists('profiles');
    }
}
