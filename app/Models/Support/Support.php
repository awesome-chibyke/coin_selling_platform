<?php

namespace App\Models\Support;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Support extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $supportMessageOpenStatus = 'open';
    public $supportMessageClosedStatus = 'closed';

    public $supportMessageUnreadStatus = 'un-read';
    public $supportMessageReadStatus = 'read';

    function support_message_array(){
        return $this->hasMany(SupportMessage::class, 'support_unique_id');
    }

    function support_sender(){
        return $this->belongsTo(User::class, 'user_id');
    }

    function getMessageStatus(){
        if($this->status === $this->supportMessageOpenStatus){
            $statusObject = (object)['class'=>'primary'];
        }

        if($this->status === $this->supportMessageClosedStatus){
            $statusObject = (object)['class'=>'danger'];
        }
        return $statusObject;
    }

    function getReadStatus(){

        $count = 0;
        if($this->read_status === $this->supportMessageUnreadStatus){
            $count++;
        }

        $allSupportMessagesCount = $this->support_message_array()->where('read_status', $this->supportMessageUnreadStatus)->count();
        return $count + $allSupportMessagesCount;

    }

}