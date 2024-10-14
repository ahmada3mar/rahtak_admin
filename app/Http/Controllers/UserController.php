<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends AdminController
{
    /*
    |--------------------------------------------------------------------------
    | index, show and destroy functions
    |--------------------------------------------------------------------------
    |
    | All Admin Controllers extends from App\Http\Controllers\Admin\AdminController
    | and have the same functionally
    | we put these functions inside AdminController to prevent code replicate
    |
    */

    protected $withInIndex = ['roles:name' ,'branch:id,name' ];
    protected $with = ['roles:name', 'branch:id,name'];


    public function search(&$query, $request)
    {
        if ($request->id != '') {
            $query->whereId($request->id);
        }

        if ($request->branch_id) {
            $query->whereHas('branch', fn($branches) => $branches->whereIn('branches.id', $request->branch_id));
        }

        if ($request->search != '') {
            $query->where('name', 'like', '%' . static::escape_like($request->search) . '%')
                ->orWhere('mobile', 'like', '%' . static::escape_like($request->search) . '%');
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($request->password);
        $user = User::create($data);
        $user->assignRole($data['roles']);
        return $user;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $Webhook
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();
        if (\array_key_exists('password', $data)) {
            $data['password'] = bcrypt($request->password);
        } else {
            unset($data['password']);
        }
        $user->update($data);
        $user->syncRoles($data['roles']);

        return $user;
    }
}
