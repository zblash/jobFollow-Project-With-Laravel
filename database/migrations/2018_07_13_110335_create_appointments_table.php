<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
            Schema::create('appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned();;
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->enum('appointment_type',['standard','planned']);
            $table->enum('payment_type',['nakit','kredi_karti','ucretsiz_hizmet']);
            $table->integer('service_id')->unsigned();
            $table->foreign('service_id')->references('id')->on('services');
            $table->integer('service_pay');
            $table->integer('employee_pay');
            $table->integer('driver_id')->unsigned();;
            $table->foreign('driver_id')->references('id')->on('drivers');
            $table->timestamp('appointment_time');
            $table->timestamp('next_appointment_time');
            $table->integer('appointment_range');
            $table->integer('is_employee_profit');
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
        Schema::dropIfExists('appointments');
    }
}
