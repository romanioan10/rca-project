<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rca_offer_logs', function (Blueprint $table) {
            $table->id();

            $table->json('request_payload');
            $table->json('response_payload')->nullable();

            $table->string('status')->default('pending');
            $table->text('error_message')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rca_offer_logs');
    }
};