<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\AuthenticationTrait;
use App\Traits\AccountActivationTrait;
use Illuminate\Support\Facades\Auth;

class AccountActivationGuard
{
    use AccountActivationTrait, AuthenticationTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->user()->email_verified_at === null){
            $this->forwardActivationMail($this->accountActivationType, $request->user());
            $uniqueId = $request->user()->unique_id;
            Auth::logout();
            return redirect()->route('account_activation', [$uniqueId])->with('error', 'Your account is yet to be activated, Please provide the TOKEN sent to your mail to activate account.');
        }
        return $next($request);
    }
}