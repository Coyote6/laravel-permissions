<?php


namespace Coyote6\LaravelPermissions\Traits;


use Illuminate\Database\Eloquent\Model;


trait PolicyValidatesModel {
	
	
	//
	// Autodetect Policy
	//
	// @optionalPolicy $autoDetectPolicy - bool
	//
	// If you are using Laravel to autodetect your policies then you do not
	// need to use this property.  If you are manually adding policy, it is
	// recommended to set this property to false, and set the $modelClass
	// property as well.
	//
	// @see $this->getModelClass()
	//
	// @return bool
	//
	protected function autoDetectPolicy (): bool {
		
		if (property_exists ($this, 'autoDetectPolicy') && $this->autoDetectPolicy === false) {
			return false;
		}
		
		return true;
		
	}
	
	//
	// Model Class
	//
	// @optionalProp $modelClass
	//
	// The $modelClass is the model's full class name.
	// Example:
	//		protected string $modelClass = Model::class;
	//
	// This property is used to make sure the model type the permission
	// is being checked on is valid.
	//
	// @see $this->modelTypeIsValid()
	//
	// @return string
	//
	public function getModelClass (): string {
		if (!property_exists ($this, 'modelClass')) {
			trigger_error ('The $modelClass property must be set to use the StandardPolicy trait if autoDetectPolicy is set to false.');
		}
		return $this->modelClass;
	}
	
	
	//
	// Model Type Is Valid
	//
	// This method just checks that the supplied model type
	// is the same as the policy expects.
	//
	// This is basically a safety net and should not have to be 
	// used and is completely unnecessary when using Laravel's
	// policy autodetect to load your policies.  It is here for
	// a backup when autodetect is off, should one want to make
	// sure that the supplied model type is valid.
	//
	// @return bool
	//
	protected function modelTypeIsValid (Model $model): bool {
		
		if (!$this->autoDetectPolicy()) {
			$class = $this->getModelClass();
			if (!$model instanceof $class) {
				return false;
			}
		}
		
		return true;
	}
	
}