<?php

namespace App\Http\Controllers\Front;

use App\Models\Settings;
use App\Models\BankDetails;
use App\Models\PaymentModal;
use Illuminate\Http\Request;
use App\Models\DisplayBanner;
use App\Models\UserBankDetails;
use App\Models\CoinConversionRate;
use App\Http\Controllers\Controller;
use App\Models\CoinMarketDetailsUpdate;

class FrontPagesController extends Controller
{
    function __construct(Settings $settings, CoinConversionRate $coinConversionRate, CoinMarketDetailsUpdate $coinMarketDetailsUpdate, UserBankDetails $userBankDetails, BankDetails $bankDetails, DisplayBanner $displayBanner, PaymentModal $paymentModal)
    {
        $this->settings = $settings;
        $this->coinConversionRate = $coinConversionRate;
        $this->coinMarketDetailsUpdate = $coinMarketDetailsUpdate;
        $this->userBankDetails = $userBankDetails;
        $this->bankDetails = $bankDetails;
        $this->displayBanner = $displayBanner;
        $this->paymentModal = $paymentModal;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coin_market_details = $this->getCoinDetails($this->coinMarketDetailsUpdate, $this->coinConversionRate);
        $coin_market_details = count($coin_market_details->data) > 0 ? $coin_market_details->data['coin_rate_and_coin_for_payments_array'] : [];

        $bankDetails = $this->bankDetails::orderBy('name', 'ASC')->get();

        $displayBanner = $this->displayBanner::where('status', $this->displayBanner->activeBannerDisplayStatus)->first();

        return view('front.index', $this->mergeData(['coin_market_details'=>$coin_market_details, 'bank_details'=>$bankDetails, 'display_banner'=>$displayBanner, 'image_folder'=>$this->displayBanner->displayBannerFileStoragePath]));
    }

    /**
     * contact
     *
     * @return \Illuminate\Http\Response
     */
    public function contact()
    {
        return view('front.contact', $this->mergeData());
    }

    /**
     * terms
     *
     * @return \Illuminate\Http\Response
     */
    public function terms()
    {
        return view('front.terms', $this->mergeData());
    }

    /**
     * policy
     *
     * @return \Illuminate\Http\Response
     */
    public function policy()
    {
        return view('front.policy', $this->mergeData());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $coin_market_details = $this->getCoinDetails($this->coinMarketDetailsUpdate, $this->coinConversionRate);
        $coin_market_details = count($coin_market_details->data) > 0 ? $coin_market_details->data['coin_rate_and_coin_for_payments_array'] : [];

        if(count($coin_market_details) > 0){
            return response()->json(['status'=>true, 'message'=>'success', 'data'=>['coin_market_data'=>$coin_market_details[strtolower($code)] ] ]);
        }
        return response()->json(['status'=>false, 'message'=>['general_error'=>['No Data was found'] ], 'data'=>[] ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getamountDetails($code, $amount)
    {
        $usdValueDetails = $this->getEstimatedAmount($amount, 'usd', strtolower($code));
        if($usdValueDetails->status === false){
            return response()->json(['status'=>false, 'message'=>['general_error'=>['No Data was found'] ], 'data'=>[] ]);
        }

        $coin_market_details = $this->getCoinDetails($this->coinMarketDetailsUpdate, $this->coinConversionRate);
        $coin_market_details = count($coin_market_details->data) > 0 ? $coin_market_details->data['coin_rate_and_coin_for_payments_array'] : [];

        if(count($coin_market_details) > 0){
            $estimated_amount = $usdValueDetails->data->estimated_amount;
            return response()->json(['status'=>true, 'message'=>'success', 'data'=>[
                'coin_market_data'=>$coin_market_details[strtolower($code)],
                'estimated_amount'=>$estimated_amount ]
                ]);
        }
        return response()->json(['status'=>false, 'message'=>['general_error'=>['No Data was found'] ], 'data'=>[] ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkTransaction($transactionId)
    {
        try{
            $transaction = $this->paymentModal::where('unique_id', $transactionId)->first();
            if($transaction === null){
                throw new \Exception('Transaction could not be found, please make sure you have provided a corect transaction ID');
            }
            return response()->json(['status'=>true, 'message'=>'You are now been redirected to transaction details page', 'data'=>[] ]);

        }catch(\Exception $exception){
            return response()->json([
                    'status'=>false,
                    'message'=>['general_error'=>[$exception->getMessage()]],
                    'data'=>[]
                ]);
        }
    }
}