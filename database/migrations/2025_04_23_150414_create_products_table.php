<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->text('description'); 
            $table->integer('rating')->default(0); 
            $table->foreignId('category_id')         
                  ->constrained('categories')      
                  ->onDelete('cascade');     
            $table->string('product_code')->nullable();
            $table->boolean('status')->default(1);   
             $table->softDeletes();  
            $table->timestamps(); 
          
        });
      
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('description')->nullable(false)->change(); // Đảm bảo không null nếu quay lại
        });
    }
};
