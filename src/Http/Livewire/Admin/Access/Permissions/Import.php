<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions;

use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelCrud\Crud\Import as CrudImport;


class Import extends CrudImport {
			

	public $fieldColumnMap = [
		'id' => '',
        'name' => '',
    ];
    
    
    protected $rules = [
        'fieldColumnMap.name' => 'required',
    ];
    
    
    protected $customAttributes = [
        'fieldColumnMap.id' => 'id',
        'fieldColumnMap.name' => 'name',
    ];
    
    
    
    protected function purposedColumnMapping () {
	    return [
			'id' => ['id', 'uuid'],
			'name' => ['name', 'title', 'label'],
		];
    }
    
    
    protected function rowValidationRules () {
	    return [
			'id' => ' nullable|string|unique:permissions,id',
			'name' => 'required|unique:permissions,name|max:191',
		];
    }
    
    
    protected function create ($fields) {
	    Permission::create($fields);
    }
    
    
	
}
