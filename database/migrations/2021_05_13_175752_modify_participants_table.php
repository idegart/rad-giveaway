<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('participants');

        Schema::create('participants', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->integer('day');
            $table->integer('month');
            $table->integer('year');

            $table->timestamps();

            $table->unique(['name', 'day', 'month', 'year']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
