<?php

namespace App\Http\Controllers\Referal;

use App\Models\User;
use App\Models\Referal;
use App\Traits\Generics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReferalController extends Controller
{
    use Generics;

    function __construct(User $user, Referal $referal){
        $this->referal = $referal;
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId = null)
    {

        $referals = $userId !== null ? $this->referal::where('refferer_unique_id', $userId)->orderBy('id', 'DESC')->get() : $this->referal::orderBy('id', 'DESC')->get();

        return view('logged.referals', ['referals'=>$referals]);
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
