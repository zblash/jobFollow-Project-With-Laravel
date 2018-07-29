<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {       
        Schema::create('employee_appointment', function (Blueprint $table) {
            $table->integer('employee_id')->unsigned();;
            $table->foreign('employee_id')->references('id')->on('employees');
            $table->integer('appointment_id')->unsigned();;
            $table->foreign('appointment_id')->references('id')->on('appointments');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_appointment');
    }
}
