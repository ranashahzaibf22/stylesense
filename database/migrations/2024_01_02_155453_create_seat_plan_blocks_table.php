<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeatPlanBlocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seat_plan_blocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('steatplan_id')->nullable()->constrained('seat_plans')->onDelete('cascade');
            $table->integer('row')->nullable();
            $table->integer('column')->nullable();
            $table->string('left')->nullable();
            $table->string('top')->nullable();
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
        Schema::dropIfExists('seat_plan_blocks');
    }
}
