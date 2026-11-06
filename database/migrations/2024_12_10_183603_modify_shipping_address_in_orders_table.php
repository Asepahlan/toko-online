<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyShippingAddressInOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable()->change();
            } else {
                $table->text('shipping_address')->nullable();
            }
            if (Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable()->change();
            } else {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'shipping_address')) {
                $table->text('shipping_address')->nullable(false)->change();
            }
            if (Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable(false)->change();
            }
        });
    }
}
