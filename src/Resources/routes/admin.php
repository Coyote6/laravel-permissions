<?php
	

use Illuminate\Support\Facades\Route;


Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {


	// 
	// Access
	//
	
	Route::prefix('/users')->name('access.')->group(function ()	{

		
		
		//
		// Permissions
		//
	
		Route::get('/permissions', Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions\Crud::class)->name('permissions');
		Route::prefix('/permissions')->name('permissions.')->group(function ()	{
			
			Route::post('/', 'Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions\Crud@search')->name('search');
	
			Route::get('/create', Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions\Create::class)->name('create');
			Route::post('/create', 'Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions\Create@storeFallback')->name('store');
		
			Route::get('/{permission}/edit', Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions\Update::class)->name('edit');
			Route::put('/{permission}/edit', 'Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions\Update@updateFallback')->name('update');
			
			Route::get('/{permission}/delete', Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions\Delete::class)->name('delete');
			Route::delete('/{permission}/delete', 'Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Permissions\Delete@destroyFallback')->name('destroy');
			
		});
		
		
		//
		// Roles
		//
		
		Route::get('/roles', Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles\Crud::class)->name('roles');
		Route::prefix('/roles')->name('roles.')->group(function ()	{
			
			Route::post('/', 'Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles\Crud@search')->name('search');
	
			Route::get('/create', Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles\Create::class)->name('create');
			Route::post('/create', 'Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles\Create@storeFallback')->name('store');
		
			Route::get('/{role}/edit', Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles\Update::class)->name('edit');
			Route::put('/{role}/edit', 'Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles\Update@updateFallback')->name('update');
			
			Route::get('/{role}/delete', Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles\Delete::class)->name('delete');
			Route::delete('/{role}/delete', 'Coyote6\LaravelPermissions\Http\Livewire\Admin\Access\Roles\Delete@destroyFallback')->name('destroy');
			
		});

	});


	
});

