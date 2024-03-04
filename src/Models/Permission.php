<?php

namespace Coyote6\LaravelPermissions\Models;


use App\Models\User;

use Coyote6\LaravelBase\Traits\GetAsOptions;
use Coyote6\LaravelBase\Traits\HasMachineNameAsId;
use Coyote6\LaravelBase\Traits\BootTraits;

use Coyote6\LaravelPermissions\Models\Role;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Permission extends Model {

    use HasFactory, 
    	GetAsOptions,
    	HasMachineNameAsId,
    	BootTraits;


	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;


    protected $fillable = [
	    'id',
        'name'
    ];
	
	public function __construct (array $attributes = []) {	
		parent::__construct($attributes);
		$this->table = config('permissions.tables.permissions') ?: parent::getTable();
	}

    
    public function roles () {
		return $this->belongsToMany (Role::class,'role_permissions');
	}
	
	
	public function roleIds () {
		$roles = $this->roles->map('id');
		return $roles;
	}
	
	

#	public function users() {
		
#	   return $this->belongsToMany (User::class,'user_permissions');
#	}
	
}
