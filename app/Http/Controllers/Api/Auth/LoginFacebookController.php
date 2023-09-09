<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class LoginFacebookController extends Controller
{

    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    public function login(Request $request)
    {

        $token = $request->token;
        $providerUser = Socialite::driver('facebook')->userFromToken($token);
        if(!$providerUser)
        {
            return $this->error('error fetching the data ,token invailed');
        }

            $userProviderId = $providerUser->id;
            $userProviderName = $providerUser->name;
        $user = User::where('provider_name','facebook')->where('provider_id',$userProviderId)->first();
        if(!$user)
        {
            $user = User::create([
                'username' => $userProviderName,
                'provider_name' => 'facebook',
                'provider_id' => $userProviderId,
                'avatar' => "https://graph.facebook.com/v3.3/$userProviderId/picture?type=large&access_token=$token"
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

