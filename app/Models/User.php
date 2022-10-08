<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Roles\RolesModel;
use App\Models\Roles\Previledges;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $nairaSymbol = '₦';

    public $normalUserType = 'user';
    public $adminUserType = 'admin';
    public $midAdminUserType = 'mid-admin';
    public $superAdminUserType = 'super-admin';

    public $userActiveAccountStatus = 'active';
    public $userBlockedAccountStatus = 'blocked';

    public $emailSubcriptionYesStatus = 'yes';
    public $emailSubcriptionNoStatus = 'no';

    public $defaultUserCurrency = 'NGN';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'unique_id',
        'country_code',
        'phone',
        'username',
        'referrer_username',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function user_bank_details(){
        return $this->hasMany(UserBankDetails::class, 'user_unique_id');
    }


    function updateUser($requestObject, $userObject){

        $userObject->email = $requestObject->email ?? $userObject->email;
        $userObject->phone = $requestObject->phone ?? $userObject->phone;
        $userObject->name = $requestObject->name ?? $userObject->name;
        $userObject->address = $requestObject->address ?? $userObject->address;
        $userObject->city = $requestObject->city ?? $userObject->city;
        $userObject->state = $requestObject->state ?? $userObject->state;
        $userObject->country = $requestObject->country ?? $userObject->country;
        $userObject->save();
        return $userObject;
    }

    function privilegeChecker($role){

        if(Auth::check()){

            $userDetails = User::find(Auth::user()->unique_id);
            $userType = $userDetails->type_of_user; //return $userType;
            $typOfUserDetails = DB::table('user_types_models')->where('type_of_user', $userType)->first();
            if($typOfUserDetails === null){
                return false;
            }

            $roleDetails = RolesModel::where('role', $role)->first();
            if($roleDetails === null){
                return false;
            }

            //get the previledges
            $priviledgesDetails = Previledges::where('role_id', $roleDetails->unique_id)->where('type_of_user_id', $typOfUserDetails->unique_id)->first();
            if($priviledgesDetails !== null){
                return true;
            }
        }

        return false;
    }

    function returnDefaultUserCurrency(){
        return $this->defaultUserCurrency;
    }
}