<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('offers', function (Blueprint $t) {
      $t->id();
      $t->string('title');
      $t->text('description')->nullable();
      $t->enum('type', ['percent','fixed','free_shipping'])->default('percent');
      $t->decimal('value', 10, 2)->nullable(); // for percent/fixed
      $t->timestamp('starts_at')->nullable();
      $t->timestamp('ends_at')->nullable();
      $t->boolean('is_active')->default(true);
      $t->string('banner_image')->nullable(); // for homepage banner
      $t->string('cta_url')->nullable();
      $t->timestamps();
    });
  }
  public function down(): void { Schema::dropIfExists('offers'); }
};
