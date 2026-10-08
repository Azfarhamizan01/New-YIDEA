<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('memberships', function(Blueprint $table){
  $table->id(); $table->foreignId('user_id')->constrained()->cascadeOnDelete(); $table->enum('plan',['free','pro','studio'])->default('free');
  $table->decimal('price',15,2)->default(0); $table->timestamp('starts_at')->nullable(); $table->timestamp('ends_at')->nullable(); $table->enum('status',['active','expired','pending'])->default('active'); $table->timestamps();
 }); }
 public function down(): void { Schema::dropIfExists('memberships'); }
};
