<?php

namespace App\Models;

use App\Models\User;
use App\Models\PaymentModal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Referal extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $pendingStatus = 'pending';
    public $processingTransferStatus = 'processing-transfer';
    public $payedStatus = 'payed';

    function referredObject(){
        return $this->belongsTo(User::class, 'reffered_unique_id');
    }

    function referrerObject(){
        return $this->belongsTo(User::class, 'refferer_unique_id');
    }

    function paymentObject(){
        return $this->belongsTo(PaymentModal::class, 'payment_unique_id');
    }

    function returnStatus(){
        if($this->status === $this->pendingStatus){
            $statusObject = (object)['class'=>'warrning'];
        }
        if($this->status === $this->processingTransferStatus){
            $statusObject = (object)['class'=>'warrning'];
        }
        if($this->status === $this->payedStatus){
            $statusObject = (object)['class'=>'warrning'];
        }
        return $statusObject;
    }
}
