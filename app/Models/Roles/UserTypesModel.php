<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTypesModel extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    function createUserTypes($request){
        //	title 	news
        $UserTypesModel = new UserTypesModel();
        $UserTypesModel->unique_id = $request->unique_id;
        $UserTypesModel->type_of_user = $request->type_of_user;
        $UserTypesModel->description = $request->description;
        $UserTypesModel->save();
        return $UserTypesModel;
    }

    function updateUserTypes($request){
        ////title 	description
        $UserTypesModel = UserTypesModel::find($request->unique_id);
        $UserTypesModel->description = $request->description ?? $UserTypesModel->description;
        $UserTypesModel->save();
        return $UserTypesModel;

    }

    function getSingleRow($uniqueId){

        return UserTypesModel::find($uniqueId);

    }

    function getAllRows($orderColumn = 'created_at', $orderType = 'desc'){

        return UserTypesModel::orderBy($orderColumn, $orderType)->get();

    }

    function getAllRowsWithPagination($noOfRow = 20, $orderColumn = 'created_at', $orderType = 'desc'){

        return UserTypesModel::orderBy($orderColumn, $orderType)->paginate($noOfRow);

    }

    function getRowsWhere($conditions, $orderColumn = 'created_at', $orderType = 'desc'){

        return UserTypesModel::where($conditions)->orderBy($orderColumn, $orderType)->get();

    }
}