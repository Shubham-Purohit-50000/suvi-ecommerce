<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('special_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('subscription_type'); // e.g., '1 Year', '2 Years'
            $table->decimal('discount', 5, 2); // percentage value, e.g., 10.00
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('special_subscriptions');
    }
};
