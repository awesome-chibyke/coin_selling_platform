<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Traits\Generics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class PasswordManagerController extends Controller
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

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($userId)
    {
        $userObject = $this->user::where('unique_id', $userId)->first();
        return view('logged.change_password', ['user'=>$userObject]);
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
            'old_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        return $validator;
    }

    public function store(Request $request, $userId)
    {

        try{
            //validate inputs
            $validate = $this->validator($request->all());
            if($validate->fails()){
                return Redirect::back()->withErrors($validate->getMessageBag());
            }

            //check the old password against user account
            $userObject = $this->user::where('unique_id', $userId)->first();
            if($userObject === null){ throw new \Exception('No Data was found'); }
            $credentials = ['email'=>$userObject->email, 'password'=>$request->old_password];
            if (Auth::attempt($credentials)) {
                $userObject->password = Hash::make($request->password);
                $userObject->save();
                return Redirect::back()->with('success', 'Your have successfully changed your password');
            }
            throw new \Exception('An error occurred, please try again');

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