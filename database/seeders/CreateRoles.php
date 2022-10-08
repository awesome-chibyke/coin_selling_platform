<?php

namespace Database\Seeders;

use App\Traits\Generics;
use App\Traits\RoleManagement;
use Illuminate\Database\Seeder;
use App\Models\Roles\RolesModel;

class CreateRoles extends Seeder
{
    use RoleManagement, Generics;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rolesArray = $this->ReturnRolesObjectfromFile();

        foreach($rolesArray as $k => $eachRolesDesc){


            $RolesModel = RolesModel::where([
                ['role', '=', $k]
            ])->first();

            if($RolesModel === null){

                $NewRolesModel = new RolesModel();
                $NewRolesModel->unique_id = $this->createNewUniqueId('roles_models', 'unique_id', 20);
                $NewRolesModel->role = $k;
                $NewRolesModel->description = $eachRolesDesc;
                $NewRolesModel->save();

            }else{

                $RolesModel->role = $k;
                $RolesModel->description = $eachRolesDesc;
                $RolesModel->save();

            }

        }
    }
}