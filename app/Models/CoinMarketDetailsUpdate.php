<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoinMarketDetailsUpdate extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    function correctAString($mainString, $wordToSearch, $replaceWith){
        return str_replace($wordToSearch, $replaceWith, $mainString);
    }

}