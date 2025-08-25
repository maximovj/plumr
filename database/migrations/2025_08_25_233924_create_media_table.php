<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // photo | video | pdf | audio
            $table->string('file_path'); // ruta al archivo
            $table->string('mime_type');
            $table->string('title');
            $table->string('slug')->unique(); // SEO
            $table->text('description')->nullable(); // SEO
            $table->json('tags')->nullable(); // etiquetas
            $table->enum('visibility', ['private', 'public', 'followers_only'])->default('public');
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
        Schema::dropIfExists('media');
    }
}
