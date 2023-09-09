<?php

namespace App\Http\Controllers\Api\Auth;

use App\Mail\SendOTP;
use App\Helpers\OTPUser;
use Illuminate\Http\Request;
use App\Models\ResetPassword;
use App\Helpers\HelpersFunctions;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;
use App\Helpers\Otps\HelperResetPassword;
use Illuminate\Support\Facades\Validator;

class ResetPasswordUserController extends Controller
{
    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    public function sendCode(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
        ]);

        $login = $request->login;
        $user = HelpersFunctions::getUser($login);
        if(!$user)
        {
            return $this->finalResponse('failed', 400, null, null,'user not found');
        }

        // make otp in reset password
        $createotp = new HelperResetPassword($user,new ResetPassword);
        $otp = $createotp->buildOtp();
        $date = $otp->returnExpireDate;

        //  send mail
        Mail::to($user->email)->send(new SendOTP($user->name,$otp->otp,$date));
        return $this->finalResponse('please check your mail.', 200);

    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string|min:8|confirmed']);


        $login = $request->login;

        $user = HelpersFunctions::getUser($login);
        if(!$user)
        {
            return $this->finalResponse('failed',400,null,null,'user not found');
        }

        $request->merge(['password' => Hash::make($request->password)]);
        $user->password = $request->password;

        if($user->save())
        {
            return $this->finalResponse('password reset succesfully',200,null,null);
        }

        else
        {
            return $this->finalResponse('success',200,'password reset succesfully');
        }
    }

    public function checkOTP(Request $request) : JsonResponse {
        $request->validate([
            'login' => 'required|string',
            'otp' => 'required|min:6|integer'
        ]);
        $user = HelpersFunctions::getUser($request->login);
        if(!$user)
        {
            return $this->finalResponse('failed',400,null,null,'user not found');

        }
        $otp = $request->otp;
            $verifyUserOtp = new HelperResetPassword($user,new ResetPassword);
            $messege = $verifyUserOtp->attempOTP($otp);

            if($messege === true || $messege[0] === true)
            {
                $token = auth('api')->login($user);
                return $this->finalResponse('success',200,['token'=>$token]);

            }
            return $this->finalResponse('failed',400,null,null,$messege);

    }
}
