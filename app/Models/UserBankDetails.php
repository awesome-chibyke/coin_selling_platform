<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use function PHPUnit\Framework\returnArgument;

class UserBankDetails extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $activeStatus = 'active';
    public $notActiveStatus = 'not-active';

    function current_user_object(){
        return $this->belongsTo(User::class, 'user_unique_id');
    }


    function getStatus($status){
        if($status === $this->activeStatus){
            return (object)[
                'class'=>'success',
                'keyword'=>strtoupper($status)
            ];
        }

        if($status === $this->notActiveStatus){
            return (object)[
                'class'=>'warning',
                'keyword'=>strtoupper($status)
            ];
        }
    }

    function getInitials($name){

        $stringToTrurn = '';

        if($name === null || $name === ''){
            return $stringToTrurn;
        }

        $explodedName = explode(' ', $name);

        if(count($explodedName) > 0){
            foreach($explodedName as $k => $eachName){
                $firstLetter = substr($eachName, 0, 1);
                $stringToTrurn .= $firstLetter;
            }
        }
        return $stringToTrurn;
    }


}