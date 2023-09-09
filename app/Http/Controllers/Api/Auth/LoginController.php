<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Resources\UserResource;
use App\Models\Otp;
use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use App\Helpers\Otps\HelperVerificationUser;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    public function login(Request $request) : JsonResponse
    {
        $request->validate(
        [
            'login' => 'required|string',
            'password' => 'required|string|min:8'
        ]);

        $credentials = $request->only('login','password');

        $login = HelpersFunctions::attemptLogin($credentials);

        // if only login failed
        if ($login === false)
        {
            return $this->finalResponse('failed',422,null,null,trans('auth.invalid_credentials'));
        }
        $token = $login['token'];
        $user = $login['user'];
        $varified = new HelperVerificationUser($user,new Otp);

        $user = new UserResource($user);

        if($varified->checkVerify()){
            return $this->finalResponse('login successfully',200,['user' =>$user , 'token'=>$token]);
        }
        return $this->finalResponse( 'failed',401,null,null,trans('auth.verified'));

    }


    public function logout(Request $request)
    {
        auth('api')->logout();
        return $this->finalResponse('success','200','logout successfully');
    }
}
