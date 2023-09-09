<?php

namespace App\Http\Controllers\Api\Auth;


use App\Models\Otp;
use App\Mail\SendOTP;
use Vonage\SMS\Message\SMS;
use Illuminate\Http\Request;
use App\Rules\VerificationType;
use App\Helpers\HelpersFunctions;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use PHPOpenSourceSaver\JWTAuth\JWTAuth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\Otps\HelperVerificationUser;

class VerifyUserController extends Controller
{


    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    /**
     * sendOTP
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function sendOTP(Request $request)
    {

        $request->validate([
            'login' => 'required|string',
            'typeVerify' => ['required', new VerificationType]
        ]);
        $user = HelpersFunctions::getUser($request->login);
        if ($user) {

            $createOtp = new HelperVerificationUser($user, new Otp);
            // check and create and return otp
            $otp = $createOtp->buildOtp();
            $date = $otp->returnExpireDate;
            if ($request->typeVerify == "email") {
                Mail::to($user->email)->send(new SendOTP($user->name, $otp->otp, $date));
                return $this->finalResponse('success', 200,'please check your mail');
            }


            // check and create and return otp in SMS
            $client = HelpersFunctions::intialSMS();
            $response = $client->sms()->send(
                new SMS($request->phone, 'Sportify', "Dear $user->name
                Please use the following OTP to verify your account: $otp->otp This OTP is valid for $date minutes.")
            );
            $message = $response->current();
            if ($message->getStatus() == 0) {
                return $this->finalResponse('success', 200,'please check your SMS.');
            }
            return $this->finalResponse("The message failed with status: " . $message->getStatus(), 400);
        }
        return $this->finalResponse('failed', 400, null, null, 'user not found');
    }



    /**
     *  checkOTP
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function checkOTP(Request $request): JsonResponse
    {
        $request->validate([
            'login' => 'required|string',
            'otp' => 'required|min:6|integer'
        ]);
        $otp = $request->otp;
        $user = HelpersFunctions::getUser($request->login);
        if (!$user) {
            return $this->finalResponse('failed', 400, null, null,'user not found');
        }
        $verifyUserOtp = new HelperVerificationUser($user, new Otp);
            $messege = $verifyUserOtp->attempOTP($otp);
            if ($messege === true || $messege[0] === true) {
                return $this->finalResponse('success', 200,'user verified successfully');
            }
            return $this->finalResponse('failed', 400, null, null, $messege);
    }






    /**
     * resendOTP
     * @param \Illuminate\Http\Request $request
     * @return JsonResponse|mixed
     */
    public function resendOTP(Request $request)
    {

        $request->validate([
            'login' => 'required|string']);

        $user = HelpersFunctions::getUser($request->login);
        if (!$user) {
            return $this->finalResponse('failed',400,null,'user not found');
        }
        $verifyUserOtp = new HelperVerificationUser($user, new Otp);
        // if user not verified
            if (!$verifyUserOtp->checkVerify()) {
                $otp = $verifyUserOtp->resendOtp();
                $date = $verifyUserOtp->returnExpireDate();
                Mail::to($user->email)->send(new SendOTP($user->name, $otp->otp, $date));
                return $this->finalResponse('success',200,'please check your mail');
            } else {
                return $this->finalResponse('failed',400,null,'user already verified');
            }
    }
}
