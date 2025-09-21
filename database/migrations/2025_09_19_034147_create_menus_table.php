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
        Schema::create('admin_menus', function (Blueprint $table) {
            $table->id();
            $table->string('scope')->comment('作用域');
            $table->string('name')->comment('名称');
            $table->string('path')->comment('路径');
            $table->string('component')->nullable()->comment('组件');
            $table->tinyInteger('disabled')->default(0)->comment('是否禁用');
            $table->string('redirect')->nullable()->comment('组件');
            $table->json('meta')->nullable()->comment('元数据');
            $table->json('routes')->nullable()->comment('路由');
            $table->bigInteger('parent_id')->unsigned()->comment('父级ID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_menus');
    }
};
