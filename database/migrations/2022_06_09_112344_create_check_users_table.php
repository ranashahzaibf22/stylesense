<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_users', function (Blueprint $table) {
            $table->id();
            $table->string('ip')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_user')->nullable();
            $table->string('code')->nullable();
            $table->string('email')->nullable();
            $table->string('category')->nullable();
            $table->integer('designer_id')->nullable();

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
        Schema::dropIfExists('check_users');
    }
}
