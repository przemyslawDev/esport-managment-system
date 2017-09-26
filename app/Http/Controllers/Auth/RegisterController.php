<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Exceptions\DontHavePermissionException;
use Auth;

class RegisterController extends Controller
{
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
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return
     */
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'avatar' => '',
            'active' => false,
            'confirmation_code' => str_random(30)
        ]);
    }

    public function confirm($confirmation_code)
    {
        if (!$confirmation_code) {
            throw new DontHavePermissionException;
        }

        $user = User::where('confirmation_code', $confirmation_code)->first();

        if(!$user) {
            throw new DontHavePermissionException;
        }

        $user->active = true;
        $user->confirmed = true;
        $user->confirmation_code = null;
        $user->save();

        if(Auth::check()) {
            Auth::logout();
        }
        Auth::login($user);
        
        return redirect()->route('dashboard');
    }

    public function showRegistrationForm()
    {
        // disable
        //return view('auth.register');
    }

    public function register(Request $request)
    {
        // disable
        /*$this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());*/
    }
}
