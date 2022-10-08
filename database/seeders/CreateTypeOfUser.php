<?php

namespace Database\Seeders;

use App\Models\User;
use App\Traits\Generics;
use App\Traits\RoleManagement;
use Illuminate\Database\Seeder;
use App\Models\Roles\UserTypesModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CreateTypeOfUser extends Seeder
{
    use RoleManagement, Generics;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userModelInstance = new User();
        $userTypeArray = $this->createTypeOfUsers($userModelInstance);

        foreach($userTypeArray as $k => $eachUserType){
            $NewUserTypeModel = new UserTypesModel();
            $NewUserTypeModel->unique_id = $this->createNewUniqueId('user_types_models', 'unique_id', 20);
            $NewUserTypeModel->type_of_user = $k;
            $NewUserTypeModel->description = $eachUserType;
            $NewUserTypeModel->save();
        }
    }
}