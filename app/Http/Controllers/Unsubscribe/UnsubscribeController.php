<?php

namespace App\Http\Controllers\Unsubscribe;

use App\Models\User;
use App\Traits\Generics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class UnsubscribeController extends Controller
{
    use Generics;

    function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($userId)
    {
        $userObject = $this->user::where('unique_id', $userId)->first();
        $userObject === null ? abort(404) : '';

        if($userObject->email_subcription === $this->user->emailSubcriptionNoStatus){ return redirect::route('login')->with('error', 'Account has already been unsubscribed'); };

        $reasons = ['Am no longer interested', 'Poor and low quality contents', 'The mails are just too much', 'other'];
        return view('auth.unsubscribe', ['reasons'=>$reasons, 'user_unique_id'=>$userObject->unique_id]);
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
            'unsubscribe_reason' => ['required', 'string'],
        ]);

        $validator->sometimes('unsubscribe_custom_reason', 'required|string|min:5', function ($input) {
            return $input->unsubscribe_reason === 'other';
        });

        return $validator;
    }

    public function store(Request $request, $userId)
    {
        try{

            $validate = $this->validator($request->all());
            if($validate->fails()){ return Redirect::back()->withErrors($validate->getMessageBag()); }

            //UPDATE THE USER TABLE
            $userObject = $this->user::where('unique_id', $userId)->first();
            if($userObject === null){ throw new \Exception('No matching data found'); }

            $userObject->unsubscribe_reason = $request->unsubscribe_reason === 'other' ? $request->unsubscribe_custom_reason : $request->unsubscribe_reason;
            $userObject->email_subcription = $this->user->emailSubcriptionNoStatus;
            $userObject->save();

            return redirect::route('login')->with('status', 'You have been successfully unsubscribed from our mailing list');

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
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
