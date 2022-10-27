<?php


namespace Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions;


use Coyote6\LaravelPermissions\Models\Permission;
use Coyote6\LaravelCrud\Crud\Crud as BaseCrud;


class Crud extends BaseCrud {	
	
	
	public $routeName = 'admin.access.permissions';
	
	public $importForm = 'laravel-permissions::admin.access.permissions.import';
	public $createForm = 'laravel-permissions::admin.access.permissions.create';
	public $updateForm = 'laravel-permissions::admin.access.permissions.update';
	public $deleteForm = 'laravel-permissions::admin.access.permissions.delete';
	
	public $createRoute = 'admin.access.permissions.create';
	public $updateRoute = 'admin.access.permissions.edit';
	public $deleteRoute = 'admin.access.permissions.delete';
		
	public $bulkExportSuccessMessage = 'The selected permissions were successfully deleted.';

	public $filters = [
		'search' => '',
		'id' => '',
		'name' => ''
	];
	
	public $columns = [
		'name' => 'Name',
	];
 
 
    
	public function advancedSearchFormFields (&$form) {
		$form->text ('search-id')
			->lwDebounce ('filters.id')
			->placeholder ('Id');
		$form->text ('name')
			->lwDebounce ('filters.name')
			->placeholder ('Name');
	}


	public function getQueryProperty() {
		
		$query = Permission::query()
			->when($this->filters['search'], fn($query, $search) => $query->multiFieldSearch('id,name', $search))
			->when($this->filters['id'], fn($query, $search) => $query->search('id', $search))
			->when($this->filters['name'], fn($query, $search) => $query->search('name', $search));

		return $this->applySorting ($query);
		
	}
	

}
