<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rca_policy_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('rca_customer_id')->nullable()->constrained('rca_customers')->nullOnDelete();
    $table->unsignedBigInteger('offer_id');
    $table->unsignedBigInteger('policy_id')->nullable();
    $table->json('request_payload');
    $table->json('response_payload')->nullable();
    $table->string('status')->default('pending');
    $table->text('error_message')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rca_policy_logs');
    }
};
