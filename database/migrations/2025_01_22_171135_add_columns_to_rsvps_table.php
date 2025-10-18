<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToRsvpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rsvps', function (Blueprint $table) {
           $table->text('nationality')->after('special')->nullable();
           $table->text('age_range')->after('nationality')->nullable();
           $table->text('salutation')->after('age_range')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rsvps', function (Blueprint $table) {
            //
        });
    }
}
