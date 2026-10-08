<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('projects', function(Blueprint $table){
  $table->id(); $table->foreignId('client_id')->constrained('users')->cascadeOnDelete(); $table->string('category'); $table->string('title'); $table->text('description');
  $table->decimal('budget',15,2); $table->unsignedInteger('timeline_days'); $table->json('tags')->nullable();
  $table->enum('status',['published','proposals_received','in_progress','paid','completed','cancelled'])->default('published');
  $table->foreignId('winner_designer_id')->nullable()->constrained('users')->nullOnDelete(); $table->timestamps();
 }); }
 public function down(): void { Schema::dropIfExists('projects'); }
};
