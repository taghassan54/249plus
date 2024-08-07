<?php 
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;


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
$data= $className::all();

return response()->json($this->generate_response(
    array(
        "message" => $model." list loaded successfully", 
        "data"    => $data
    ), 'SUCCESS'
));

    }

    public  function getAllModelNames()
{
    $modelPath = app_path('Models');
    $modelFiles = File::allFiles($modelPath);
    $modelNames = [];

    foreach ($modelFiles as $file) {
        $modelNames[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
    }

    return response()->json($this->generate_response(
        array(
            "message" => " list loaded successfully", 
            "data"    => $modelNames
        ), 'SUCCESS'
    ));
    
}

}