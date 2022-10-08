<?php

namespace App\Models;

use App\Models\MailReceivers;
use App\Models\MailAttachments;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BulkMail extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $readStatus = 'read';
    public $unReadStatus = 'un-read';

    public $sendToAllUsers = 'all';
    public $sendToSelectedUsers = 'selected';

    function mail_receivers(){
        return $this->hasMany(MailReceivers::class, 'mail_unique_id');
    }

    function mail_attachments(){
        return $this->hasMany(MailAttachments::class, 'mail_unique_id');
    }
}