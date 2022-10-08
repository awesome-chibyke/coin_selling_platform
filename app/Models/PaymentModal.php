<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentModal extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'unique_id';
    public $incrementing = false;
    protected $keyType = 'string';

    public $paymentModalPendingStatus = 'pending';//status for crypto payment still under pocessing
    public $paymentModalConfirmedStatus = 'confirmed';//status for crypto payment still thathas been fully comfirmed
    public $paymentModalRetryTransferStatus = 'retry-transfer';//status for crypto payment still thathas been fully comfirmed
    public $paymentModalExpiredStatus = 'expired';//status for crypto payment still thathas been fully comfirmed

    public $paymentModalProcessingTransfer = 'processing_transfer';//status for transfer still under processing
    public $paymentModalCompletedStatus = 'completed';//status for transfer that has been full confirmed

    public $paymentModalFailedStatus = 'failed';//status for crypto payment or transfer to user account that failed

    public $nowPaymentOption = 'now_payment';
    public $flutterWaveOption = 'flutter_wave';

    public $coinSaleActionType = 'coin_sale';
    public $transferSettlementType = 'settlement_by_transfer';
    public $transferSettlementTypeForReferal = 'settlement_by_transfer_for_referal';

    function user_object(){
        return $this->belongsTo(User::class, 'user_unique_id');
    }

    function returnPaymentStatus(){
        if($this->status === $this->paymentModalPendingStatus){
            $statusDetailsObject = (object)['value'=>'Pending Transaction', 'class'=>'warning'];
        }
        if($this->status === $this->paymentModalRetryTransferStatus){
            $statusDetailsObject = (object)['value'=>'Pending Fund Transer', 'class'=>'info'];
        }
        if($this->status === $this->paymentModalConfirmedStatus){
            $statusDetailsObject = (object)['value'=>'Payment Of Crypto Confirmed', 'class'=>'info'];
        }
        if($this->status === $this->paymentModalProcessingTransfer){
            $statusDetailsObject = (object)['value'=>'Processing Fund Transfer to User Bank Account', 'class'=>'info'];
        }
        if($this->status === $this->paymentModalCompletedStatus){
            $statusDetailsObject = (object)['value'=>'Transaction Completed', 'class'=>'success'];
        }
        if($this->status === $this->paymentModalFailedStatus){
            $statusDetailsObject = (object)['value'=>'Transaction Failed', 'class'=>'danger'];
        }
        if($this->status === $this->paymentModalExpiredStatus){
            $statusDetailsObject = (object)['value'=>'Expired Crypto Transaction', 'class'=>'danger'];
        }
        return $statusDetailsObject;
    }

    private function savePaymentModal($requestObject){
        $paymentModal = new PaymentModal();
        $paymentModal->unique_id = $requestObject->unique_id;
        $paymentModal->user_unique_id = $requestObject->user_unique_id;
        $paymentModal->amount_transfered = $requestObject->amount_transfered;
        $paymentModal->description = $requestObject->description;
        $paymentModal->action_type = $requestObject->action_type;
        $paymentModal->payment_option = $requestObject->payment_option;
        $paymentModal->status = $requestObject->status;
        $paymentModal->reference = $requestObject->reference;
        $paymentModal->deposit_transaction_id = $requestObject->deposit_transaction_id;
        $paymentModal->local_currency = $requestObject->local_currency;
        $paymentModal->save();
        return $paymentModal;
    }

}