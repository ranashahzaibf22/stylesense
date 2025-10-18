<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('db_name',191)->unique();
            $table->string('db_user');
            $table->text('db_password')->nullable();
            $table->string('port')->default('3306');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *`
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
