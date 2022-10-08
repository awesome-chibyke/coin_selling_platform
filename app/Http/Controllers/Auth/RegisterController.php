<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Settings;
use App\Traits\Generics;
use Illuminate\Http\Request;
use App\Mail\WelcomeToDataSeller;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use App\Traits\AccountActivationTrait;
use App\Traits\AuthenticationTrait;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    use Generics, AuthenticationTrait, AccountActivationTrait;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    //show the registration page
    public function showRegistrationForm()
    {
        $data = [];
        $data['referrer_username'] = null;
        if(isset($_GET['ref'])){
            $data['referrer_username'] = $_GET['ref'];
        }
        return view('auth.register', $data);
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Settings $settings)
    {
        $this->middleware('guest');

        $this->settings = $settings;
    }//referrer_username

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $validator =  Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],//confirmed
            'phone' => ['nullable', 'numeric', 'min:8'],
            'check_thick' => ['required'],
        ]);

        $validator->sometimes('referrer_username', 'exists:users,username', function ($input) {
            return $input->referrer_username !== null;
        });

        return $validator;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data, string $uniqueId, string $username)
    {
        return User::create([
            'unique_id' => $uniqueId,
            'name' => $data['name'],
            'username' => $username,
            'email' => $data['email'],
            'phone' => $data['phone'],
            'referrer_username' => $data['referrer_username'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        $uniqueId = $this->createNewUniqueId('users', 'unique_id', 20);
        $username = $this->createNewUniqueId('users', 'username', 20);
        event(new Registered($user = $this->create($request->all(), $uniqueId, $username)));

        //$this->guard()->login($user);
        //send account activtion mail
        $this->forwardActivationMail($this->accountActivationType, $user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                    : redirect('account_activation/'.$user->unique_id)->with('status', 'Registration was successful, An account activation token was sent to your email. Please provide token to activate account');//redirect($this->redirectPath())
    }
}