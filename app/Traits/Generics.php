<?php

namespace App\Traits;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use phpDocumentor\Reflection\DocBlock\Tags\Author;

trait Generics{

    function createObject($array){
        return json_decode(json_encode($array));
    }

    function random_string ( $type = 'alnum', $len = 60 )
    {
        switch ( $type )
        {
            case 'alnum'	:
            case 'numeric'	:
            case 'nozero'	:

                switch ($type)
                {
                    case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric'	:	$pool = '0123456789';
                        break;
                    case 'nozero'	:	$pool = '123456789';
                        break;
                }

                $str = '';
                for ( $i=0; $i < $len; $i++ )
                {
                    $str .= substr ( $pool, mt_rand ( 0, strlen ( $pool ) -1 ), 1 );
                }
                return $str;
                break;
            case 'unique' : return md5 ( uniqid ( mt_rand () ) );
                break;
        }
    }

    //create a unique id
    public function createNewUniqueId($table_name, $column, $lenght = 60, $type = 'alnum'){

        /*$unique_id = Controller::picker();*/
        $unique_id = $this->random_string($type, $lenght);
        if($lenght == 60){
            $unique_id = substr($unique_id, 0, strlen($unique_id)/2);
        }

        //check for the database count from the database"unique_id"
        $rowcount = DB::table($table_name)->where($column, $unique_id)->count();

        if($rowcount == 0){

            if(empty($unique_id)){
                return $this->createNewUniqueId($table_name, $column);
            }else{
                return $unique_id;
            }

        }else{
            return $this->createNewUniqueId($table_name, $column);
        }

    }

}
