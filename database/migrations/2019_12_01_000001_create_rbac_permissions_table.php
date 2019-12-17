<?php

use Eiixy\Rbac\Models\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRbacPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->default(0)->comment('父级权限');
            $table->string('name')->comment('权限名称');
            $table->string('icon')->nullable()->comment('图标');
            $table->tinyInteger('type')->default(Permission::TYPE_CATALOG)->comment('权限类型');
            $table->string('url')->nullable()->comment('菜单url');
            $table->string('keyword')->nullable()->comment('权限标识');
            $table->integer('sort')->default(50)->comment('排序');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rbac_permissions');
    }
}
