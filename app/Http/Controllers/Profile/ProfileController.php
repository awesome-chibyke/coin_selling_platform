<?php

namespace App\Http\Controllers\Profile;

use App\Models\User;
use App\Traits\Generics;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
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
    public function index($userId = null)
    {

        try{

            $userObject = $userId === null ? Auth()->user() : $this->user::where('unique_id', $userId)->first();
            if($userObject === null){ throw new \Exception('User record does not exist');}
            $country = $this->returnCountry();

            return view('logged.profile', ['user_object'=>$userObject, 'country'=>$country]);

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }


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
    public function edit($userId = null)
    {
        try{

            $userObject = $userId === null ? Auth()->user() : $this->user::where('unique_id', $userId)->first();
            if($userObject === null){ throw new \Exception('User record does not exist');}

            return view('logged.edit_profile', ['user_object'=>$userObject]);

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }

    protected function validateUpdateValues(array $data)
    {
        $validator =  Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'numeric', 'min:8'],
            'address' => ['nullable', 'string', 'min:5'],
            'city' => ['nullable', 'string', 'min:3'],
            'state' => ['nullable', 'string', 'min:3'],
            'country' => ['nullable', 'string', 'min:3'],
        ]);

        return $validator;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userId)
    {
        try{

            $this->validateUpdateValues($request->all())->validate();

            $userObject = $this->user::where('unique_id', $userId)->first();
            if($userObject === null){ throw new \Exception('User record does not exist');}

            $updatedUserObject = $this->user->updateUser($request, $userObject);

            return $updatedUserObject ? Redirect::back()->with('success', 'Update was succesful') : Redirect::back()->with('error', 'Update of user records failed');

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
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