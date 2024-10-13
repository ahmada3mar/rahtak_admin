<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends AdminController
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

    protected $withInIndex = ['permissions:name,group'];
    protected $with = ['permissions:name,group'];




    public function index(Request $request)
    {
        $model = $this->getModelName();
        $query = $model::query();

        empty($this->withInIndex) ?: $query->with($this->withInIndex);

        $this->search($query, $request);
        $perPage = $request->perPage ?? 10;

        $direction = $request->sortDesc == 'true' ? 'desc' : 'asc';
        $query->orderBy($request->sortBy ?? $this->default_sort_by,  $direction);

        $allPermissions = collect(['permissions' => Permission::all(['name', 'group'])]);

        return $allPermissions->merge($query->paginate($perPage));
    }

    public function search(&$query, $request)
    {
        if ($request->id != '') {
            $query->whereId($request->id);
        }



        if ($request->search != '') {
            $query->where('name', 'like', '%' . static::escape_like($request->search) . '%');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  string|int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->getModelName();
        $query = $model::query();

        empty($this->with) ?: $query->with($this->with);
        $query =  $this->trashed ? $query->withTrashed() : $query;

        $role = $query->findOrFail($id);

        $role->available_permissions = Permission::all(['name', 'group'])->groupBy("group");
        return $role;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);

        return $role;
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $role->update([
            'name' => $request->name
        ]);
        $role->syncPermissions($request->permissions);

        return $role;
    }
}
