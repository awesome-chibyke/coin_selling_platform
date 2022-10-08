<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DisplayBanner extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $displayBannerFileStoragePath = 'display_banner/';
    public $displayBannerDefaultFileName = 'display_banner.png';

    public $activeBannerDisplayStatus = 'active';
    public $InActiveBannerDisplayStatus = 'in-active';

    function getDisplayStatus(){
        if($this->status === $this->activeBannerDisplayStatus){
            return (object)['class'=>'success'];
        }

        if($this->status === $this->InActiveBannerDisplayStatus){
            return (object)['class'=>'warning'];
        }
    }
}