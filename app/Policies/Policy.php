<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\HandlesAuthorization;

class Policy
{
    use HandlesAuthorization;

    public $key;

    public function __construct()
    {
        $this->key = Str::plural(Str::snake(str_replace("Policy" ,"",class_basename($this)) , " "));
    }


	/**
	 * Determine whether the user can create models.
	 *
	 * @param User $user
	 * @param $model
	 *
	 * @return mixed
	 */
	public function create(User $user)
	{
		return $user->hasPermissionTo('create ' . $this->key );
	}

	/**
	 * Determine whether the user can delete the model.
	 *
	 * @param User $user
	 * @param $model
	 *
	 * @return mixed
	 */
	public function delete(User $user)
	{
		return $user->hasPermissionTo( 'delete ' . $this->key);
	}

	/**
	 * Determine whether the user can permanently delete the model.
	 *
	 * @param User $user
	 * @param $model
	 *
	 * @return mixed
	 */
	public function forceDelete(User $user)
	{
		return $user->hasPermissionTo('destroy ' . $this->key);
	}

	/**
	 * Determine whether the user can restore the model.
	 *
	 * @param User $user
	 * @param $model
	 *
	 * @return mixed
	 */
	public function restore(User $user)
	{
		return $user->hasPermissionTo('restore ' . $this->key);
	}

	/**
	 * Determine whether the user can update the model.
	 *
	 * @param User $user
	 * @param $model
	 *
	 * @return mixed
	 */
	public function update(User $user)
	{
		return $user->hasPermissionTo('update ' . $this->key);
	}

	/**
	 * Determine whether the user can view the model.
	 *
	 * @param User $user
	 * @param $model
	 *
	 * @return mixed
	 */
	public function view(User $user)
	{

		return $user->hasPermissionTo('view ' . $this->key);
	}

	/**
	 * @param User $user
	 *
	 * @param $model
	 *
	 * @return mixed
	 */
	public function viewAny(User $user)
	{
		return $user->hasPermissionTo('view ' . $this->key);
	}
}
