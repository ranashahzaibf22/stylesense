<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRsvpDesignerHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rsvp_designer_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rsvp_id')->nullable()->constrained('rsvps')->onDelete('cascade');
            $table->foreignId('designer_id')->nullable()->constrained('designers')->onDelete('cascade');
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
        Schema::dropIfExists('rsvp_designer_histories');
    }
}
