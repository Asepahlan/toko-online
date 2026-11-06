<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'total_sales')) {
                $table->integer('total_sales')->default(0);
            }
            if (!Schema::hasColumn('products', 'is_featured')) {
                $table->boolean('is_featured')->default(false);
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'total_sales')) {
                $table->dropColumn('total_sales');
            }
            if (Schema::hasColumn('products', 'is_featured')) {
                $table->dropColumn('is_featured');
            }
        });
    }
};
