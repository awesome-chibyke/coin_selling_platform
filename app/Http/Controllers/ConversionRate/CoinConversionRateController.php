<?php

namespace App\Http\Controllers\ConversionRate;

use App\Traits\Generics;
use Illuminate\Http\Request;
use App\Models\CoinConversionRate;
use App\Http\Controllers\Controller;
use App\Models\CoinMarketDetailsUpdate;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class CoinConversionRateController extends Controller
{
    use Generics;

    function __construct(CoinMarketDetailsUpdate $coinMarketDetailsUpdate, CoinConversionRate $coinConversionRate)
    {
        $this->coinMarketDetailsUpdate = $coinMarketDetailsUpdate;
        $this->coinConversionRate = $coinConversionRate;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        try{
            $coinsForPayment = $this->coinMarketDetailsUpdate::where('id', 1)->first();
            if($coinsForPayment === null){ throw new \Exception('Coin Market detail is currently not available'); }

            $coinsForPaymentArray = json_decode($coinsForPayment->content);
            $coinCoversionRateArray = $this->coinConversionRate::get();

            //generate a new arra that holds both the conversion rate and coin data
            $combinedCoinRateAndCoinForPaymentsArray = $this->combineConversionRateAndCoinData($coinsForPaymentArray, $coinCoversionRateArray);

            return view('logged.add_coin_rate', ['combined_coin_rate_and_coin_for_payments_array'=>$combinedCoinRateAndCoinForPaymentsArray, 'image_base_url'=>$this->nowPaymentMainPath, 'coinMarketInstance'=>$this->coinMarketDetailsUpdate]);

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function validator(array $data)
    {
        $validator =  Validator::make($data, [
            'rate_in_local_currency' => ['required', 'numeric'],
            'coin_code' => ['required', 'string']
        ]);

        return $validator;
    }

    public function storeUpdatePayment(Request $request)
    {

        try{

            $validate = $this->validator($request->all());
            if($validate->fails()){
                return response()->json([ 'status'=>false, 'message'=>$validate->getMessageBag(), 'data'=>[] ]);
            }

            //check if the currency exists
            $checkCoinExistence = $this->coinConversionRate::where('unique_id', $request->coin_code)->first();

            $coinConversionRateObject = $checkCoinExistence !== null ?
                $coinConversionRateObject = $this->updateCoinConversionRate($checkCoinExistence, $request) :
                $coinConversionRateObject = $this->saveCoinConversionRate($this->coinConversionRate, $request);

            return $coinConversionRateObject ?
                response()->json([
                    'status'=>true,
                    'message'=>'Request was successfully processed',
                    'data'=>$coinConversionRateObject
                ]) : throw new \Exception('An error ocurred, please again later');

        }catch(\Exception $exception){
            return response()->json([
                'status'=>false,
                'message'=>['general_error'=>[$exception->getMessage()]],
                'data'=>[]
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
    public function destroy($id)
    {
        //
    }
}