<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\PaymentModal;
use Illuminate\Http\Request;
use App\Models\Support\Support;
use App\Models\UserBankDetails;
use Illuminate\Support\Facades\Auth;
use App\Models\Support\SupportMessage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserBankDetails $userBankDetails, PaymentModal $paymentModal, Support $support, SupportMessage $supportMessage, User $user)
    {
        //$this->middleware('auth');
        $this->userBankDetails = $userBankDetails;
        $this->paymentModal = $paymentModal;
        $this->support = $support;
        $this->supportMessage = $supportMessage;
        $this->user = $user;

        $this->filterByMonth = 'month';
        $this->filterByYear = 'year';
        $this->filterByDay = 'day';
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth()->user();

        $userBankDetails = $this->userBankDetails::where('user_unique_id', $user->unique_id)->orderBy('id', 'DESC')->first();
        $userBankDetails = ['user_bank_detail'=>$userBankDetails];

        //get the transactions summary
        $allTransferDetails = $this->getAllWithdrawals($user, $this->paymentModal, $this->user);

        //complted deposit details
        $allCompletedDepositsDetails = $this->getAllCompletedDepositDetails($user, $this->paymentModal, $this->user);

        //deposits
        $allTransactionsDetails = $this->getAllDepositDetails($user, $this->paymentModal, $this->user);

        //support mesage summary
        $allUnreadSupportMessageDetails = $this->getReadStatusCountForAllMessage($user, $this->support, $this->supportMessage);
        $monthsArray = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $filters = ['filter_by_month'=>$this->filterByMonth, 'filter_by_year'=>$this->filterByYear, 'filter_by_day'=>$this->filterByDay, 'months_array'=>$monthsArray];

        return view('logged.dashboard', array_merge($userBankDetails, $allTransferDetails, $allCompletedDepositsDetails, $allTransactionsDetails, $allUnreadSupportMessageDetails, $filters) );
    }

    function getBartChartDetails($filterKeyword, $date, $date2 = null){

        //try{
            $userObject = Auth()->user();
            $data = [];

            if($filterKeyword === $this->filterByDay){
                $data = $this->filterByDayMethod($date, $userObject);
            }

            if($filterKeyword === $this->filterByMonth){
                $data = $this->filterByMonthMethod($date, $userObject);
            }

            if($filterKeyword === $this->filterByYear){
                $data = $this->filterByYearMethod($date, $date2, $userObject);
            }

            return response()->json([
                'status'=>true,
                'message'=>'Data was returned successfully',
                'data'=>$data
            ]);

        // }catch(\Exception $exception){
        //     return response()->json([
        //         'status'=>false,
        //         'message'=>['general_error'=>[$exception->getMessage()]],
        //         'data'=>[]
        //     ]);
        // }

    }

    private function filterByDayMethod($date, $userObject){
        $dateDetails = $this->getDateDetails($date);

        $depositData = $this->getTransactionData($this->paymentModal, $this->user, $dateDetails->next_month, $userObject, $dateDetails->start_date, $dateDetails->end_date, $this->paymentModal->coinSaleActionType);
        $withdrawalData = $this->getTransactionData($this->paymentModal, $this->user, $dateDetails->next_month, $userObject, $dateDetails->start_date, $dateDetails->end_date, $this->paymentModal->transferSettlementType);

        $daysDetails = $this->returnDaysArray($dateDetails->start_date, $dateDetails->month, $dateDetails->year);

        $transactionData = $this->buildTransactionObjects($daysDetails['days_object'], $daysDetails['days_object'], $depositData, $withdrawalData);

        $finalTransactionData = $this->getDataArray($transactionData);
        return array_merge(['x_axis'=>$daysDetails['days_array']], $finalTransactionData);
    }

    private function filterByMonthMethod($date, $userObject){
        $explodedDate = explode('-', $date);

        $year = $explodedDate[0];
        $startDate = Carbon::parse($year.'-01'.'-31')->toDateTimeString();
        $endDate = Carbon::parse($year.'-12'.'-31')->toDateTimeString();
        $depositData = $this->getTransactionData($this->paymentModal, $this->user, 13, $userObject, $startDate, $endDate, $this->paymentModal->coinSaleActionType);
        $withdrawalData = $this->getTransactionData($this->paymentModal, $this->user, 13, $userObject, $startDate, $endDate, $this->paymentModal->transferSettlementType);

        $monthsDetails = $this->returnMonthsDetails();

        $transactionData = $this->buildTransactionObjects($monthsDetails['months_object'], $monthsDetails['months_object'], $depositData, $withdrawalData, 1);

        $finalTransactionData = $this->getDataArray($transactionData);
        return array_merge(['x_axis'=>$monthsDetails['months_array']], $finalTransactionData);
    }

    private function filterByYearMethod($year1, $year2, $userObject){
        $startDate = Carbon::parse($year1.'-01'.'-31')->toDateTimeString();
        $endDate = Carbon::parse($year2.'-12'.'-31')->toDateTimeString();

        $depositData = $this->getTransactionData($this->paymentModal, $this->user, 13, $userObject, $startDate, $endDate, $this->paymentModal->coinSaleActionType);
        $withdrawalData = $this->getTransactionData($this->paymentModal, $this->user, 13, $userObject, $startDate, $endDate, $this->paymentModal->transferSettlementType);

        $yearDetails = $this->returnYearDetails($year1, $year2);

        $transactionData = $this->buildTransactionObjects($yearDetails['years_object'], $yearDetails['years_object'], $depositData, $withdrawalData, 0);

        $finalTransactionData = $this->getDataArray($transactionData);
        return array_merge(['x_axis'=>$yearDetails['years_array']], $finalTransactionData);
    }

}
