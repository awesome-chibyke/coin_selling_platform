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

class AddRolesController extends Controller
{
    use Generics;

    function __construct(RolesModel $rolesModel, UserTypesModel $userTypesModel, Previledges $previledges)
    {
        //$this->middleware('auth');
        $this->userTypesModel = $userTypesModel;
        $this->rolesModel = $rolesModel;
        $this->previledges = $previledges;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userTypeId)
    {
        //get all the user type
        $allUserType = $this->userTypesModel::get();
        //get the roles and then the user type concerned and send to view
        $userTypeDetails = $this->userTypesModel->getSingleRow($userTypeId);
        $allRoles = $this->rolesModel->getAllRows();
        $data = ['user_type'=>$userTypeDetails, 'all_roles'=>$allRoles, 'all_user_type'=>$allUserType];
        return view('roles.add_roles_to_user', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $userTypeId)
    {
        //validate the input
        $validate = $this->handleValidation($request->all());
        if($validate->fails()){
            return response()->json(['status'=>false, 'message'=>$validate->getMessageBag()]);
        }//option

//assign,remove
        //loop through the roles and add to the db
        $allRole = $request->role_id;
        $updateInsertStatus = 0;
        foreach($allRole as $k => $eachRoleId){

            $UserTypePreviledgeDetail = $this->previledges->getSingleRowsWhere([
                ['type_of_user_id', '=', $userTypeId],
                ['role_id', '=', $eachRoleId],
            ]);

            $previledgeArray = [
                'type_of_user_id'=>$userTypeId,
                'role_id'=>$eachRoleId,
            ];

            if($UserTypePreviledgeDetail === null){

                if($request->option === 'assign'){
                    $previledgeArray['unique_id'] = $this->createNewUniqueId('previledges', 'unique_id', 20);
                    $objectToInsert = $this->createObject($previledgeArray);//create the object to be inserted
                    $prviledgeObject = $this->previledges->createPreviledges($objectToInsert);
                    $message = 'Selected Roles have been assigned';
                }else if($request->option === 'remove'){
                    continue;
                }

            }else{

                if($request->option === 'assign'){
                    continue;
                }else if($request->option === 'remove'){
                    $UserTypePreviledgeDetail->forceDelete();
                    $prviledgeObject = true;
                    $message = 'Selected Roles dropped for this type of user';
                }

            }

            if($prviledgeObject){
                $updateInsertStatus = 1;
            }

        }

        if($updateInsertStatus == 1){
            $returndetails = ['status'=>true, 'message'=>$message];
        }else{
            $returndetails = ['status'=>false, 'message'=>'An error occurred, please try again'];
        }
        return response()->json($returndetails);

    }

    function handleValidation(array $data){

        $validator = Validator::make($data, [
            'role_id' => 'required|array',
            'role_id.*' => 'required|string'
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