<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void {
    Schema::create('offer_product', function (Blueprint $t) {
      $t->id();
      $t->foreignId('offer_id')->constrained()->cascadeOnDelete();
      $t->foreignId('product_id')->constrained()->cascadeOnDelete();
      $t->unique(['offer_id','product_id']);
    });
  }
  public function down(): void { Schema::dropIfExists('offer_product'); }
};
