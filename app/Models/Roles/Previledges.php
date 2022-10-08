<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Previledges extends Model
{
    use SoftDeletes;

    //primary key
    protected $primaryKey = 'unique_id';
    //public $incrementing = false;
    protected $keyType = 'string';

    function createPreviledges($request){
        //	unique_id 	type_of_user_id 	role_id
        $Previledges = new Previledges();
        $Previledges->unique_id = $request->unique_id;
        $Previledges->type_of_user_id = $request->type_of_user_id;
        $Previledges->role_id = $request->role_id;
        $Previledges->save();
        return $Previledges;
    }

    function updatePreviledges($request){
        ////	unique_id 	type_of_user_id 	role_id
        $Previledges = Previledges::find($request->unique_id);
        $Previledges->type_of_user_id = $request->type_of_user_id ?? $Previledges->type_of_user_id;
        $Previledges->role_id = $request->role_id ?? $Previledges->role_id;
        $Previledges->save();
        return $Previledges;

    }

    function getSingleRow($uniqueId){

        return Previledges::find($uniqueId);

    }

    function getAllRows($orderColumn = 'created_at', $orderType = 'desc'){

        return Previledges::orderBy($orderColumn, $orderType)->get();

    }

    function getAllRowsWithPagination($noOfRow = 20, $orderColumn = 'created_at', $orderType = 'desc'){

        return Previledges::orderBy($orderColumn, $orderType)->paginate($noOfRow);

    }

    function getRowsWhere($conditions, $orderColumn = 'created_at', $orderType = 'desc'){

        return Previledges::where($conditions)->orderBy($orderColumn, $orderType)->get();

    }

    function getSingleRowsWhere($conditions){

        return Previledges::where($conditions)->first();

    }

    function returnUserTypeRoleStatus($userTypeId, $RoleId){
        $Previledges = Previledges::where('type_of_user_id', $userTypeId)->where('role_id', $RoleId)->first();
        if($Previledges === null){
            $roleStatus = ['status'=>'NOT-ASSIGNED', 'label'=>'warning'];
        }else{
            $roleStatus = ['status'=>'ASSIGNED', 'label'=>'success'];
        }
        return $roleStatus;
    }
}