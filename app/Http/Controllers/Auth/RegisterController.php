<?php

namespace UniqueBank\Http\Controllers\Auth;

use UniqueBank\User;
use UniqueBank\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Carbon\Carbon;

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
    protected $redirectTo = '/home';

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
        $date = Carbon::now()->subYears(18)->format('d/m/Y');

        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date_format:d/m/Y', 'before_or_equal:'.$date],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \UniqueBank\User
     */
    protected function create(array $data)
    {
        $array = explode('/', $data['birth_date']);
        $temp = $array[0];
        $array[0] = $array[2];
        $array[2] = $temp;
        $data['birth_date'] = implode('-', $array);

        return User::create([
            'name' => $data['name'],
            'birth_date' => $data['birth_date'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
