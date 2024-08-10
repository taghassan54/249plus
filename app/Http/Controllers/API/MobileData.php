<?php

namespace App\Http\Controllers\API;

use Exception;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\File;


class MobileData extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, $model)
    {
//$modelClass = resolve('App\\Models\\' . $modelName);
        try {
            $modelClass = resolve('App\\Models\\' . $model);
            $className = get_class($modelClass);
            $data = $className::all();

            return response()->json($this->generate_response(
                array(
                    "message" => $model . " list loaded successfully",
                    "data" => $data
                ), 'SUCCESS'
            ));
        } catch (Exception $exception) {
            return response()->json($this->generate_response(
                array(
                    "message" => $model . " " . $exception->getMessage(),
                    "data" => null,
                    "status_code" => $exception->getCode(),
                ), 'BAD_REQUEST'
            ));
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllModelNames()
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
                "data" => $modelNames
            ), 'SUCCESS'
        ));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, $model)
    {

        try{
        $modelClass = resolve('App\\Models\\' . $model);
        $className = get_class($modelClass);

        $validatorRoles=[
            "role_menus"=>$this->get_validation_rules('role_menus'),
        ];
        // Decode each JSON string into an associative array
        $validatorArray = array_map(function($jsonString) {
            return json_decode($jsonString, true);
        }, $request->validator??[]);

        foreach ($validatorArray as $role ){
            $validatorRoles[$role['name']] =  $this->get_validation_rules($role['validation_rule'], $role['required']);
        }

        $validator = Validator::make($request->all(), $validatorRoles);
        $validation_status = $validator->fails();
        if($validation_status){
            return response()->json($this->generate_response(
                array(
                    "message" => $model . " Validation Error",
                    "data" => $validator->errors()
                ), 'BAD_REQUEST'
            ));
        }

        if (!check_access([$request->role_menus], true)) {
            throw new Exception("Invalid request", 400);
        }



        }catch(Exception $e){
            return response()->json($this->generate_response(
                array(
                    "message" => $e->getMessage(),
                    "status_code" => $e->getCode()
                )
            ));
        }


    }

}