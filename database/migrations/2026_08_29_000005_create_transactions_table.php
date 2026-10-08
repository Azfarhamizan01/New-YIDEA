<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('transactions', function(Blueprint $table){
  $table->id(); $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete(); $table->foreignId('client_id')->constrained('users'); $table->foreignId('designer_id')->nullable()->constrained('users')->nullOnDelete();
  $table->decimal('amount',15,2); $table->decimal('platform_fee',15,2)->default(0); $table->enum('type',['escrow','payout','refund','membership'])->default('escrow');
  $table->enum('status',['pending','paid','held','released','failed','refunded'])->default('pending'); $table->string('external_id')->nullable()->index(); $table->timestamps();
 }); }
 public function down(): void { Schema::dropIfExists('transactions'); }
};
