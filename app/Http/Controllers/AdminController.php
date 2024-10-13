<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * specify which column should sorted by as default
     */
    protected $default_sort_by = "created_at";

    /**
     * define all relations that will be joined in show function
     */
    protected $with = [];

    protected $withInIndex = [];
    /**
     * show deleted data
     */
    protected $trashed = false;

    public function __construct()
    {
        $model_name = $this->getModelName();
        $this->authorizeResource($model_name, $model_name);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $model = $this->getModelName();
        $query = $model::query();

        empty($this->withInIndex) ?: $query->with($this->withInIndex);

       $this->search($query, $request);
        $perPage = $request->perPage ?? 10;

        $direction = $request->sortAsc == 'true' ? 'asc' : 'desc';
        $query->orderBy($request->sortBy ?? $this->default_sort_by,  $direction);

        // get only trashed data
        if ($this->trashed) {
            $query->onlyTrashed();
        }

        return $query->paginate($perPage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $Brand
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $model = $this->getModelName();
        $query = $model::query();

        empty($this->with) ?: $query->with($this->with);
        $query =  $this->trashed ? $query->withTrashed() : $query;

        return $query->where( "id", $id)->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = $this->getModelName();
        $resource = $model::findOrFail($id);
        $resource->delete();
    }

    public function trashed(Request $request , $id = null)
    {
        $resource_name = Str::plural(strtolower(str_replace("Controller", "", class_basename($this))));
        abort_unless(auth()->user()->hasPermissionTo("view deleted $resource_name"),403);

        $this->trashed = true;
        return $id ? $this->show($id) : $this->index($request);
    }

    public static function escape_like($text)
    {
        $search = array('%', '_');
        $replace   = array('\%', '\_');
        return str_replace($search, $replace, $text);
    }

    public function search(&$query, $request)
    {
        if ($request->name != '') {
            $query->where('name', 'like', '%' . static::escape_like($request->name) . '%');
        }
    }

    public function getModelName()
    {
        return "App\\Models\\" . str_replace("Controller", "", class_basename($this));
    }

}

