<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class LoginGoogleController extends Controller
{
    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    public function login(Request $request)
    {
        $token = $request->token;
        $providerUser = Socialite::driver('google')->userFromToken($token);
        if(!$providerUser)
        {
            return $this->error('error fetching the data ,token invailed');
        }

            $userProviderId = $providerUser->id;
            $userProviderName = $providerUser->name;
        $user = User::where('provider_name','google')->where('provider_id',$userProviderId)->first();
        if(!$user)
        {
            $user = User::create([
                'username' => $userProviderName,
                'provider_name' => 'google',
                'provider_id' => $userProviderId,
                'avatar' => $providerUser->getAvatar()
            ]);
        }
        $arr = ['provider_name' => $user->provider_name, 'provider_id' => $userProviderId];
        $token = auth('api')->setTTL(604800)->attempt($arr);
        if(!$token) {
            return $this->error('invaild credentials',401);
        }
        return $this->success(['user'=>$user , 'token'=>$token,'register successfully']);
        }
}

