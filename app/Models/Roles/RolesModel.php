<?php

namespace App\Models\Roles;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RolesModel extends Model
{
    use SoftDeletes;

    //primary key
    protected $primaryKey = 'unique_id';
    //public $incrementing = false;
    protected $keyType = 'string';

    function createRoles($request){
        //	title 	news
        $RolesModel = new RolesModel();
        $RolesModel->unique_id = $request->unique_id;
        $RolesModel->role = $request->role;
        $RolesModel->description = $request->description;
        $RolesModel->save();
        return $RolesModel;
    }

    function updateRole($request){
        ////title 	description
        $RolesModel = RolesModel::find($request->unique_id);
        $RolesModel->role = $request->role ?? $RolesModel->role;
        $RolesModel->description = $request->description ?? $RolesModel->description;
        $RolesModel->save();
        return $RolesModel;

    }

    function getSingleRow($uniqueId){

        return RolesModel::find($uniqueId);

    }

    function getAllRows($orderColumn = 'created_at', $orderType = 'desc'){

        return RolesModel::orderBy($orderColumn, $orderType)->get();

    }

    function getAllRowsWithPagination($noOfRow = 20, $orderColumn = 'created_at', $orderType = 'desc'){

        return RolesModel::orderBy($orderColumn, $orderType)->paginate($noOfRow);

    }

    function getRowsWhere($conditions, $orderColumn = 'created_at', $orderType = 'desc'){

        return RolesModel::where($conditions)->orderBy($orderColumn, $orderType)->get();

    }
}