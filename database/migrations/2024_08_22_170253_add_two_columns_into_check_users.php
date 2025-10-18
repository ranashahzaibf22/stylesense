<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTwoColumnsIntoCheckUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('check_users', function (Blueprint $table) {
            $table->integer('event_id')->after('designer_id')->nullable();
            $table->integer('show_id')->after('designer_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('check_users', function (Blueprint $table) {
            $table->dropColumn(['event_id','show_id']);
        });
    }
}
