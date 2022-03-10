<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_raw_data_imports', function (Blueprint $table) {
            $table->id();
            $table->string('log_sheet')->nullable();
            $table->string('date')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('invoice_date')->nullable();
            $table->string('payer')->nullable();
            $table->string('payer_name')->nullable();
            $table->string('gross_weight')->nullable();
            $table->string('transporter_name')->nullable();
            $table->string('container_id')->nullable();
            $table->string('destination')->nullable();
            $table->string('no_of_packs')->nullable();
            $table->string('driver_no')->nullable();
            $table->boolean('is_processed')->default(false);
            $table->string('file_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_raw_data_imports');
    }
};
