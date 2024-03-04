<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserRolesTable extends Migration {

	public function getTableNames ():array {
		return config('permissions.tables', 'user_roles');
	}
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		$tables = $this->getTableNames();
		Schema::create($tables['user-roles'], function (Blueprint $table) use ($tables) {
			
			$table->uuid('user_id');
			$table->string ('role_id', 95);
			
			$table->primary(['user_id', 'role_id']);
			
			$table->foreign('user_id')->references('id')->on($tables['users'])->onDelete('cascade')->onUpdate('cascade');
			$table->foreign('role_id')->references('id')->on($tables['roles'])->onDelete('cascade')->onUpdate('cascade');
			
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		$tables = $this->getTableNames();
		Schema::dropIfExists($tables['user-roles']);
	}

}
