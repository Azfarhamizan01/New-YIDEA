<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('proposals', function(Blueprint $table){
  $table->id(); $table->foreignId('project_id')->constrained()->cascadeOnDelete(); $table->foreignId('designer_id')->constrained('users')->cascadeOnDelete();
  $table->decimal('price',15,2); $table->unsignedInteger('delivery_days'); $table->text('cover_letter');
  $table->enum('status',['submitted','shortlisted','accepted','rejected','withdrawn'])->default('submitted'); $table->timestamps();
  $table->unique(['project_id','designer_id']);
 }); }
 public function down(): void { Schema::dropIfExists('proposals'); }
};
