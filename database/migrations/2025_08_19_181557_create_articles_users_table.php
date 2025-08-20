<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles_users', function (Blueprint $table) {
            $table->id();

            // Llave foránea para `user_id` desde `users`
            $table->foreignId('user_id')
            ->constrained('users')
            ->references('id')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            // Llave foránea para `article_id` desde `articles`
            $table->foreignId('article_id')
            ->constrained('articles')
            ->references('id')
            ->cascadeOnDelete()
            ->cascadeOnUpdate();

            $table->unique(['user_id', 'article_id']);

            $table->softDeletes();
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
        Schema::dropIfExists('articles_users');
    }
}
