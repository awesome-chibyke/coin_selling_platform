<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait DashboardTrait{

    function getAllCompletedDepositDetails($user, $paymentModalModelInstance, $userModelInstance){
        $allCompletedDepositsQuery = $this->paymentModal::where('status', $paymentModalModelInstance->paymentModalConfirmedStatus)->where('action_type', $paymentModalModelInstance->coinSaleActionType);
        $allCompletedDepositsQuery = $user->type_of_user === $userModelInstance->normalUserType ?
        $allCompletedDepositsQuery->where('user_unique_id', $user->unique_id) : $allCompletedDepositsQuery;
        $allCompletedDepositsCount = $allCompletedDepositsQuery->count();
        $allCompletedDepositsSum = $allCompletedDepositsQuery->sum('amount_in_usd');
        $allCompletedDepositsTransactions = $allCompletedDepositsQuery->get();
        return [
            'all_completed_deposits_count'=>$allCompletedDepositsCount,
            'all_completed_deposits__sum'=>$allCompletedDepositsSum,
            'all_completed_deposits_transactions'=>$allCompletedDepositsTransactions
        ];
    }

    function getAllDepositDetails($user, $paymentModalModelInstance, $userModelInstance){
        //deposits
        $allTransactionsQuery = $user->type_of_user === $userModelInstance->normalUserType ?
        $paymentModalModelInstance::where('action_type', $paymentModalModelInstance->coinSaleActionType)->where('user_unique_id', $user->unique_id) : $paymentModalModelInstance::where('action_type', $paymentModalModelInstance->coinSaleActionType);
        $allTransactionsCount = $allTransactionsQuery->count();
        $allTransactionsSum = $allTransactionsQuery->sum('amount_in_usd');
        $allTransactions = $allTransactionsQuery->get();

        return [
            'all_transactions_count'=>$allTransactionsCount,
            'all_transactions_sum'=>$allTransactionsSum,
            'all_transactions'=>$allTransactions,
        ];

    }

    function getAllWithdrawals($user, $paymentModalModelInstance, $userModelInstance){
        //get the transactions summary
        $allPendingTransactionsQuery = $paymentModalModelInstance::where('action_type', $paymentModalModelInstance->transferSettlementType);
        $allPendingTransactionsQuery = $user->type_of_user === $userModelInstance->normalUserType ? $allPendingTransactionsQuery->where('user_unique_id', $user->unique_id) : $allPendingTransactionsQuery;
        $allTransfersCount = $allPendingTransactionsQuery->count();
        $allTransfers = $allPendingTransactionsQuery->get();
        $allTransfersSum = $allPendingTransactionsQuery->sum('amount_transfered');
        return [
            'all_transfers_count'=>$allTransfersCount,
            'all_transfers'=>$allTransfers,
            'all_transfers_sum'=>$allTransfersSum
        ];
    }

    function returnDaysArray($date, $month, $year){
        $noOfDaysInMonth = Carbon::parse($date)->daysInMonth;
        $count = 1; $daysArray = []; $daysObject = [];
        while($count < $noOfDaysInMonth + 1){
            $theCount = $count < 10 ? '0'.$count : $count;
            $daysArray[] = $year.'-'.$month.'-'.$theCount;
            $daysObject[$theCount] = 0;
            $count++;
        }
        return ['days_array'=>$daysArray, 'days_object'=>$daysObject];
    }

    function getTransactionData($paymentModalModelInstance, $userModelInstance, $nextMonth, $userObject, $startDate, $endDate, $typeOfTransaction){

        $transactionStatus = $typeOfTransaction === $paymentModalModelInstance->coinSaleActionType ? $paymentModalModelInstance->paymentModalConfirmedStatus  : $paymentModalModelInstance->paymentModalCompletedStatus;

        $signForQuery = $nextMonth >= 12 ? '<=' : '<';
        $depositDataQuery = $paymentModalModelInstance::where('created_at', '>=', $startDate)
        ->where('created_at', $signForQuery, $endDate)
        ->where('action_type', $typeOfTransaction)
        ->where('status', $transactionStatus);

        $depositDataQuery = $userObject->type_of_user === $userModelInstance->normalUserType ? $depositDataQuery->where('user_unique_id', $userObject->unique_id) : $depositDataQuery;
        $depositData = $depositDataQuery->get();
        return $depositData;
    }

    function getDateDetails($date){
        $explodedDate = explode('-', $date);
        $startDate = Carbon::parse($explodedDate[0].'-'.$explodedDate[1].'-01')->toDateTimeString();
        $nextMonth = $explodedDate[1] + 1;
        $endDate = $nextMonth > 12 ? Carbon::parse($explodedDate[0].'-12-31')->toDateTimeString() : Carbon::parse($explodedDate[0].'-'.$nextMonth.'-01')->toDateTimeString();

        return (object)['start_date'=>$startDate, 'end_date'=>$endDate, 'next_month'=>$nextMonth, 'month'=>$explodedDate[1], 'year'=>$explodedDate[0]];
    }

    function buildTransactionObjects($daysObjectForDeposit, $daysObjectForWithdrawal, $depositDataFromDb, $withdrawalDataFromDb, $key = 2){
        if(count($depositDataFromDb) > 0){
            foreach($depositDataFromDb as $k => $eachDeposit){
                $explodedDate = explode(' ', $eachDeposit->created_at);
                $explodedDate = explode('-', $explodedDate[0]);

                $daysObjectForDeposit[$explodedDate[$key]] = $daysObjectForDeposit[$explodedDate[$key]] + $eachDeposit->amount_in_usd;
            }
        }
        if(count($withdrawalDataFromDb) > 0){
            foreach($withdrawalDataFromDb as $k => $eachWithdrawal){
                $explodedDate = explode(' ', $eachWithdrawal->created_at);
                $explodedDate = explode('-', $explodedDate[0]);

                $daysObjectForWithdrawal[$explodedDate[$key]] = $daysObjectForWithdrawal[$explodedDate[$key]] + $eachWithdrawal->amount_transfered;
            }
        }
        return['withdrawal'=>$daysObjectForWithdrawal, 'deposit'=>$daysObjectForDeposit];
    }

    function getDataArray($transactionData){
        //get the arrays
        $withdrawalArray = []; $depositArray = [];
        foreach($transactionData['withdrawal'] as $k => $eachWithdrawal){
            $withdrawalArray[] = $eachWithdrawal;
        }
        foreach($transactionData['deposit'] as $k => $eachDeposit){
            $depositArray[] = $eachDeposit;
        }
        return ['withdrawal'=>$withdrawalArray, 'deposit'=>$depositArray];
    }

    function returnMonthsDetails(){
        $monthsArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $monthsObject = ['01'=>0, '02'=>0, '03'=>0, '04'=>0, '05'=>0, '06'=>0, '07'=>0, '08'=>0, '09'=>0, '10'=>0, '11'=>0, '12'=>0];

        return ['months_array'=>$monthsArray, 'months_object'=>$monthsObject];
    }

    function returnYearDetails($yearA, $yearB){
        $yearsArray = []; $yearsObject = [];
        $endYear = $yearB + 1;
        while($yearA < $endYear){
            $yearsArray[] = $yearA;
            $yearsObject[$yearA] = 0;
            $yearA++;
        }
        return ['years_array'=>$yearsArray, 'years_object'=>$yearsObject];
    }
}
