<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\VaildateHelper\VaildateError;
use App\Models\Otp;
use App\Models\User;
use App\Mail\SendOTP;
use App\Rules\VerificationType;
use Vonage\SMS\Message\SMS;
use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Otps\HelperVerificationUser;

class RegisterController extends Controller
{
    public function __construct(Request $request)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        HelpersFunctions::setLang($request);
    }


    /**
     * register function
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function register(Request $request) : JsonResponse
    {
        $validator  = $request->validate(
        [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email',
        'phone' => 'required|string|max:20|unique:users,phone',
        'password' => 'required|string|min:8|confirmed',
            ]);

        $credentials = $request->only('email','password');
        $request->merge(['password' => Hash::make($request->password)]);
        $username = explode('@', $request->email)[0];
        $user = User::create([
            'name' => $request->name,
            'username' => $username,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone ,
            'role' => 'no_role',
        ]);

        $createOtp = new HelperVerificationUser($user, new Otp);
            // check and create and return otp
            $otp = $createOtp->buildOtp();
            $date = $otp->returnExpireDate;
            if ($request->typeVerify == "email") {
                Mail::to($user->email)->send(new SendOTP($user->name, $otp->otp, $date));
                return $this->finalResponse('success', 200,'please check your mail');
            }

        return $this->finalResponse("user created successfully",200);

    }


}
