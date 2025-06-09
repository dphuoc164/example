<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm trường image vào bảng posts
     */
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Thêm cột image có thể null, đặt sau cột title
            $table->string('image')->nullable()->after('title'); 
        });
    }

    /**
     * Xóa trường image khỏi bảng posts khi rollback
     */
    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            // Xóa cột image
            $table->dropColumn('image');
        });
    }
};
