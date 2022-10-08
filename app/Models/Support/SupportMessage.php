<?php

namespace App\Models\Support;

use App\Models\User;
use App\Models\SupportFiles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SupportMessage extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    function main_support(){
        return $this->belongsTo(Support::class, 'support_unique_id');
    }

    function support_files_array(){
        return $this->hasMany(SupportFiles::class, 'support_message_unique_id');
    }

    function sender(){
        return $this->belongsTo(User::class, 'sender_id');
    }

    function receiver(){
        return $this->belongsTo(User::class, 'reciever_id');
    }
}
