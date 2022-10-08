<?php
namespace App\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait RoleManagement{

    function returnRolesArray(){
        return [
                'course-management'=>'Addition and management of courses',
                'category-management'=>'Addition and management of categories',
                'premium-plan-setup-and-management'=>'Setup of premium plans and its management',
                'signal-setup-and-management'=>'Addition of signals to the system and its management',
                'user-list-and-management'=>'View list o user on the application and manage them',
                'user-active-premium'=>'View active premium for a user',
                'user-premium'=>'access to premium section, go premium area',
                'testimony-management'=>'view all added testimony and manage them',
                'admin-view-referral-transactions'=>'Access to view all referral transactions',
                'user-view-referrals'=>'Referral information view for users',
                'roles-management'=>'Access to the roles management platform',
                'faqs-setup'=>'Access to add, edit and delete faqs',
                'system-settings'=>'major system setup',
                'view-courses'=>'allows one to have access to the list of all the courses available for trading'
            ];
    }

    public function ReturnRolesObjectfromFile()
    {
        $fileContents = File::get(storage_path('app/public/roles/roles.json'));
        return json_decode($fileContents);
    }

    public function saveRolesToFile($roleKey, $roleValue)
    {
        //View all the users on the platform
        $rolesObject = $this->ReturnRolesObjectfromFile();
        if($rolesObject === null){
            $rolesObject = [$roleKey => $roleValue];
        }else{
            $rolesObject->$roleKey = $roleValue;
        }

        //Storage::put('roles/roles.json', json_encode($rolesObject));
        Storage::put('public/roles/roles.json', json_encode($rolesObject));
    }

    function createTypeOfUsers($userModelInstance){
        $userTypeArray = (object)[
            $userModelInstance->normalUserType => 'normal user of the application',
            $userModelInstance->adminUserType => 'first level admin',
            $userModelInstance->midAdminUserType => 'mid level admin',
            $userModelInstance->superAdminUserType => 'superior admin'
        ];
        return $userTypeArray;
    }

}