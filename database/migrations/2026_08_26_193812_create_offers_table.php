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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('deal_id')->nullable()->constrained()->nullOnDelete(); 
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete(); 
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete(); 
            $table->string('offer_number')->unique();
            $table->string('title');
            $table->date('offer_issued'); 
            $table->date('offer_valid')->nullable(); 
            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax_rate', 5, 2);
            $table->decimal('tax_amount', 10, 2);
            $table->decimal('total', 10, 2);
            $table->string('status')->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
