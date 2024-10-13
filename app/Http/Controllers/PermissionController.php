<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends AdminController
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



        /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $Permission = Permission::create($data);
        return $Permission;
    }


        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Webhook  $Webhook
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $Permission)
    {
        $data = $request->all();
        return $Permission->update($data);
    }

}
