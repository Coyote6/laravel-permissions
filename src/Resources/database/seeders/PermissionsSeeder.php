<?php

namespace Coyote6\LaravelPermissions\Database\Seeders;


use Coyote6\LaravelBase\Traits\ReadsCsv;
use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelPermissions\Models\Role;

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
	    
	    foreach ($this->getItemsFromCSV ($this->dir . $this->permsFile) as $item) {
	        Permission::create([
	            'name' => $item['name']
	        ]);
        }
    }
    
    protected function importRoles () {
	    
	    foreach ($this->getItemsFromCSV ($this->dir . $this->rolesFile) as $item) {
	        Role::create([
	            'name' => $item['name']
	        ]);
        }
    }
    
    protected function importRolePermissions () {
	    
	    foreach ($this->getItemsFromCSV ($this->dir . $this->rolePermsFile) as $item) {
	        DB::table('role_permissions')->insert([
	            'role_id' => $item['role_id'],
	            'permission_id' => $item['permission_id']
	        ]);
        }
    }
}
