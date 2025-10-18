<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumanToShowsTemplateId extends Migration
{
    /**
     * Run the migrations.
     *2
     * @return void
     */
    public function up()
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->foreignId('templates_id')->nullable()->after('image')->constrained('templates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shows', function (Blueprint $table) {
            $table->dropForeign(['templates_id']);
            $table->dropColumn(['templates_id']);
        });
    }
}
