<?php



namespace Coyote6\LaravelPermissions\Policies;


use Coyote6\LaravelPermissions\Traits\AdminAccessTrait;
use Illuminate\Auth\Access\HandlesAuthorization;


class BasePolicy {

	use AdminAccessTrait,
		HandlesAuthorization;

}

