<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadRsvpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_rsvps', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('instagram')->nullable();
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
            $table->string('designer')->nullable();
            $table->string('portfolio')->nullable();

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
        Schema::dropIfExists('upload_rsvps');
    }
}
