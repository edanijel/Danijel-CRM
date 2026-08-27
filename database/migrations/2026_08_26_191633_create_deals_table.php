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
        Schema::create('deals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('company_id')->nullable()->constrained()->nullOnDelete(); 
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete(); 
            $table->string('title');
            $table->string('description')->nullable();
            $table->decimal('value', 10, 2);
            $table->char('currency', 3);
            $table->unsignedTinyInteger('probability')->nullable(); // 0-100%
            $table->date('expected_close_date')->nullable(); 
            $table->string('status')->default('new');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deals');
    }
};
