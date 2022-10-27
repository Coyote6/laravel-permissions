<?php

namespace Coyote6\LaravelPermissions\Database\Seeders;


use Coyote6\LaravelBase\Traits\ReadsCsv;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PermissionsSeeder extends Seeder {
	
	use ReadsCSV;
	
	protected string $permsFile = 'permissions.csv';
	protected string $rolesFile = 'roles.csv';
	protected string $rolePermsFile = 'role-permissions.csv';
	
	protected string $dir = __DIR__ . '/../exports/';	

    public function run () {
	    
	    $this->importPermissions();
	    $this->importRoles();
	    $this->importRolePermissions();
	    
    }
    
    protected function importPermissions () {
	    
	    foreach ($this->getItemsFromCSV ($dir . $permsFile) as $item) {
	        DB::table('permissions')->insert([
	            'name' => $item['name']
	        ]);
        }
    }
    
    protected function importRoles () {
	    
	    foreach ($this->getItemsFromCSV ($dir . $rolesFile) as $item) {
	        DB::table('roles')->insert([
	            'name' => $item['name']
	        ]);
        }
    }
    
    protected function importRolePermissions () {
	    
	    foreach ($this->getItemsFromCSV ($dir . $rolePermsFile) as $item) {
	        DB::table('permissions')->insert([
	            'role_id' => $item['role_id'],
	            'permission_id' => $item['permission_id']
	        ]);
        }
    }
}
