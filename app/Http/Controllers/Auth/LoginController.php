<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
     */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->middleware('guest')->except('logout');
        $this->user = $user;
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'nullable|string',
            'password'        => 'nullable|string',
        ]);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        if ($user = $this->user->findByEmail($request->email)) {
            if (Hash::check($request->password, $user->password)) {
                if ($user->start_date > date('Y-m-d H:i:s')) {
                    return redirect()->back()->withErrors(__('message.start_date_error', [
                        'date' => date('Y年m月d日', strtotime($user->start_date)),
                    ]), 'login');
                }
                if ($user->isCancelationUser()) {
                    return redirect()->back()->withErrors(__('message.denied_cancelation_user'), 'login');
                }
                $this->guard()->login($user);
            }
        }
    }
}
