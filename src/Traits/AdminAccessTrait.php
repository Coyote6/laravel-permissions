<?php

  
namespace Coyote6\LaravelPermissions\Traits;


trait AdminAccessTrait {

	public function before ($user, $ability) {
		if ($user->hasRole ('Administrator')) {
			return true;
		}
	}
  
}