<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolePermissionsTable extends Migration {


	public function getTableNames ():string {
		return config('permissions.tables', 'role_permissions');
	}
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$tables = $this->getTableNames();
		Schema::create($tables['role_permissions'], function (Blueprint $table) use ($tables) {
			
			$table->string('role_id', 95);
			$table->string('permission_id',95);
			
			$table->primary(['role_id','permission_id']);
			
			$table->foreign('role_id')->references('id')->on($tables['roles'])->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('permission_id')->references('id')->on($tables['permissions'])->onDelete('cascade')->onUpdate('cascade');
			
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$tables = $this->getTableNames();
		Schema::dropIfExists($tables['role_permissions']);
	}

}
