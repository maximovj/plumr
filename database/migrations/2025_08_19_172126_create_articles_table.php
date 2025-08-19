<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('slug'); //  ruta de acceso (ruta amigable)
            $table->string('cover')->nullable();
            $table->string('author')->nullable();
            $table->string('profession')->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('summary')->nullable();
            $table->text('header')->nullable();
            $table->text('content')->nullable();
            $table->text('footer')->nullable();
            $table->json('tags')->nullable();
            $table->json('network_social')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->string('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->boolean('is_publish')->default(true);

            $table->timestamp('published_at')->useCurrent();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
