<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Events\TestPusher;
use Illuminate\Http\Request;
use App\Traits\Payments\FlutterWaveTrait;
use App\Events\FlutterWaveTransferConfirmation;

class MyTestControllerr extends Controller
{
    use FlutterWaveTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($paymentModalInstance, $pageCount = 1)
    {
        //return $this->getAllBeneficiaries();
        //return $this->deleteABeneficiary('1568613');
        //return $this->getUsdtBepToken();
        //return $this->getAvailableCurrencies();
        //return $this->getAvailableCoinForPayments();
        //return $this->createPayment(100, 'New order', 'kjshdugegyu', 'btc');
        // $text = ['name'=>'Chibueze Samuel Agbo', 'age'=>30];
        // event(new FlutterWaveTransferConfirmation($text));
        //PusherFactory::make()->trigger('my-channel-oo', 'my-event-oo', ['data' => $TradingSignal]);
        //return $this->getEstimatedAmount(1, 'usd', 'busd');
        //return $this->getWalletBalance('NGN');
        //return Carbon::parse('2022-02-01')->daysInMonth;

        //$jwtToken = $this->getJwtToken();
        //return $this->getListOfPayments(1, '2022-09-16', '2022-09-17', $jwtToken->data);

        $this->nubanVerify('6234649753', );



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