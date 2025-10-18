<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsvpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsvps', function (Blueprint $table) {
            $table->id();
            $table->string('f_name')->nullable();
            $table->string('l_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('insta')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('category')->nullable();

            //press
            $table->string('company')->nullable();
            $table->string('artical_url')->nullable();
            $table->string('work_email')->nullable();

            //buyer
            $table->string('buyer_store')->nullable();
            $table->string('buyer_category')->nullable();

            //PHOTOGRAPHERS
            $table->string('website')->nullable();
            $table->string('photography')->nullable();


             //guest
            $table->string('code')->nullable();

            $table->integer('parent')->nullable();
            $table->integer('user_id')->default(0);
            $table->string('contact_id')->nullable();
            $table->integer('designer_id')->nullable();
            $table->integer('waiting')->default(0);
            $table->integer('pre_approved')->default(0);
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
        Schema::dropIfExists('rsvps');
    }
}
