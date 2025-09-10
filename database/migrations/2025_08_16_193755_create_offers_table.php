<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('offers', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('description')->nullable();
      $table->enum('type', ['percent','fixed','free_shipping'])->default('percent');
      $table->decimal('value', 10, 2)->nullable(); // for percent/fixed
      $table->timestamp('starts_at')->nullable();
      $table->timestamp('ends_at')->nullable();
      $table->boolean('is_active')->default(true);
      $table->string('banner_image')->nullable(); // for homepage banner
      $table->string('cta_url')->nullable();
      $table->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('offers'); }
};
