<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles;

use Coyote6\LaravelPermissions\Models\Role;
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
			'id' => ' nullable|string|unique:roles,id',
			'name' => 'required|unique:roles,name|max:255',
		];
    }
    
    
    protected function create ($fields) {
	    Role::create($fields);
    }
        
	
}
