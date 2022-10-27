<?php
	
	
namespace Coyote6\LaravelPermissions\Traits;


use Coyote6\LaravelPermissions\Models\Role;


trait UserRoles {

	public function roles() {
		return $this->belongsToMany (Role::class, 'user_roles')->orderBy('name', 'asc');
	}
	
}