<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('customer_special_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('special_subscription_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('special_subscription_id')->references('id')->on('special_subscriptions')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_special_subscriptions');
    }
};
