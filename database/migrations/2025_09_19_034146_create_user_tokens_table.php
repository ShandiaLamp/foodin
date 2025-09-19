<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_user_tokens', function (Blueprint $table) {
            $table->id();
            $table->text('token')->comment('Token');
            $table->bigInteger('user_id')->unsigned()->comment('用户ID');
            $table->string('scope')->nullable()->comment('作用域');
            $table->dateTime('expire_at')->nullable()->comment('过期时间');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_user_tokens');
    }
};
