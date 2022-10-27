<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions;


use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelCrud\Crud\Delete as CrudDelete;


class Delete extends CrudDelete {
	
		
	public Permission $model;
	public $title = 'Delete Permission';

	protected $crudRoute = 'admin.access.permissions';
	protected $route = 'admin.access.permissions.delete';
	public $routeKey = 'permission';
	public $successMessage = 'The permission was successfully deleted.';
	public $confirmationMessage = 'Are you sure you wish to delete permission: <strong>%s</strong>?';
	
	

	public function mount (Permission $permission = null) {
		if (is_null ($permission)) {
			$permission = Permission::make();
		}
		$this->model = $permission;
	}
	
	
	public function show (Permission $permission) {
		if ($this->model->isNot($permission)) {
			$this->model = $permission;
		}		
		$this->emitUp ('toggleDeleteModal');
	}
	
	
	public function destroyFallback (Permission $permission) {
		$this->model = $permission;
		return $this->processDestroyFallback();
	}
	
    
    protected function confirmationMessage () {
		return sprintf ($this->confirmationMessage, $this->model->name);
	}
 

}
