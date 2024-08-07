<?php 

use App\Http\Controllers\Controller;

class MobileData extends Controller {

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$model)
    {

return $model;

    }

}