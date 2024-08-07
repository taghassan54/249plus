<?php 
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class MobileData extends Controller {

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$model)
    {
//$modelClass = resolve('App\\Models\\' . $modelName);
$modelClass = resolve('App\\Models\\' . $model);
$className = get_class($modelClass);
return $className;

    }

}