<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions;


use Coyote6\LaravelPermissions\Models\Permission;

use Coyote6\LaravelCrud\Crud\Create as CrudCreate;
use Coyote6\LaravelCrud\Traits\DefaultCreateMount;


class Create extends CrudCreate {


	use DefaultCreateMount;

		
	public Permission $model;
	protected $crudRoute = 'admin.access.permissions';
	protected $route = 'admin.access.permissions.create';
	public $title = 'Create Permission';
	public $successMessage = 'The permission was successfully created.';
	
	

	public function modelClass () {
		return Permission::class;
	}

	
	
	protected function formFields (&$form) {
	    
		$form->text('name')
			->lwDebounce('model.name')
			->required()
			->label ('Name')
			->addAttribute('placeholder', 'Name')
			->addRule('unique:permissions,name')
			->addRule('max:191');
	    
	}
    

}
