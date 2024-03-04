<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration {
	
	
	public function getTableName ():string {
		return config('permissions.tables.roles', 'roles');
	}
	
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create($this->getTableName(), function (Blueprint $table) {
			$table->string('id', 95)->primary();
            $table->string('name', 95)->unique();
		});
	}
	
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists($this->getTableName());
	}
	
}
