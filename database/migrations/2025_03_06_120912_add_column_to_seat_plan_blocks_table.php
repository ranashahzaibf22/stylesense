<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToSeatPlanBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seat_plan_blocks', function (Blueprint $table) {
            $table->string('type')->nullable()->after('top');
            $table->decimal('height', 8, 2)->nullable()->after('type');
            $table->decimal('width', 8, 2)->nullable()->after('height');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seat_plan_blocks', function (Blueprint $table) {
            //
        });
    }
}
