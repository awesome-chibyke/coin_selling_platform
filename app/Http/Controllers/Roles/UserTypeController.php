<?php

namespace App\Http\Controllers\Roles;

use App\Http\Controllers\Controller;
use App\Models\Roles\Previledges;
use App\Models\Roles\RolesModel;
use App\Models\Roles\UserTypesModel;
use App\Traits\Generics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UserTypeController extends Controller
{
    use Generics;

    function __construct(UserTypesModel $userTypesModel)
    {
        //$this->middleware('auth');
        $this->userTypesModel = $userTypesModel;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //select the user types
        $allUserTypes = $this->userTypesModel->getAllRows();
        $data = ['all_user_type'=>$allUserTypes];
        return view('roles.all_user_type', $data);//return the view
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.add_user_type');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        //validate the input
        $validate = $this->handleValidation($request->all());
        if($validate->fails()){
            return Redirect::back()->withErrors($validate->getMessageBag());
        }

        //loop through the roles and add to the db
        $allUserType = $request->type_of_user;
        $allDescription = $request->description;
        $insertStatus = 0; $errorMessage = [];

        //check if all the values are unique
        foreach($allUserType as $k => $eachUserType){
            $roleChecker = UserTypesModel::where('type_of_user', $eachUserType)->first();
            if($roleChecker !== null){
                $errorMessage[] = $eachUserType.' '.($k+1).' already exists';
            }
        }
        if(count($errorMessage) > 0){
            return Redirect::back()->withErrors(['type_of_user'=>$errorMessage]);
        }

        foreach($allUserType as $k => $eachUserType){

            $unique_id = $this->createUniqueId('user_types_models', 'unique_id');
            $userTypeObjectForInsert = $this->createObject([//create an object
                'unique_id'=>$unique_id,
                'type_of_user'=>$eachUserType,
                'description'=>$allDescription[$k]
            ]);
            $userTypeObject = $this->userTypesModel->createUserTypes($userTypeObjectForInsert);//insert the object to db

            if($userTypeObject){
                $insertStatus = 1;
            }

        }

        if($insertStatus == 1){
            return Redirect::back()->with('status', 'User Type(s) was successfully added');
        }
        return Redirect::back()->with('error', 'An error occurred, please try again');

    }

    function handleValidation(array $data){

        $validator = Validator::make($data, [
            'type_of_user' => 'required|array',
            'type_of_user.*' => 'required|string'
        ]);

        return $validator;

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}