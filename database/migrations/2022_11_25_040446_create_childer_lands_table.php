<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChilderLandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('childer_lands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_land_id')
                ->constrained('parent_lands')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->string('rental_retribution')->nullable();
            $table->enum('utilization_engagement_type', ['pinjam_pakai', 'pakai_sendiri', 'sewa_sip_bmd', 'retribusi']);
            $table->string('utilization_engagement_name')->nullable();
            $table->string('allotment_of_use');
            $table->string('coordinate');
            $table->string('large');
            $table->string('present_condition')->nullable();
            $table->string('assets_value')->nullable();
            $table->date('validity_period_of')->nullable();
            $table->date('validity_period_until')->nullable();
            $table->string('engagement_number')->unique()->nullable();
            $table->date('engagement_date')->nullable();
            $table->string('description')->nullable();
            $table->string('application_letter')->nullable();
            $table->string('agreement_letter')->nullable();
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
        Schema::dropIfExists('childer_lands');
    }
}
