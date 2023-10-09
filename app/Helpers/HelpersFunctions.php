<?php
namespace App\Helpers;

use Vonage\Client;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\FootballCoach;
use App\Models\FootballPlayer;
use Vonage\Client\Credentials\Basic;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class HelpersFunctions
{
    /**
     * login phone or email
     * @param array $credentials
     * @return mixed
     */
    public static function attemptLogin($credentials) : mixed
    {
        if(is_numeric($credentials['login'])){
            $arr = ['phone' => $credentials['login'],'password'=>$credentials['password']];
        }else
        {
            $arr = [ 'email' => $credentials['login'],'password'=>$credentials['password']];
        }
        $token = auth('api')->setTTL(604800)->attempt($arr);
        if (!$token) {
            return false;
        }
        $user = auth('api')->user();
        return ["token"=>$token,"user"=>$user];
    }

    public static function getUser($login) : mixed
    {
        if(is_numeric($login))
        {
            $user = User::where('phone',$login)->first();
            if($user){
                return $user;
            }
        }
        $user = User::where('email',$login)->first();
        if($user){
            return $user;
        }
        return false;
    }
    public static function intialSMS(){
        $basic  = new Basic("09e0419d", "e7cXPCvP2YPzEYay");
        $client = new Client($basic);
        return $client;
    }


    public static function setLang(Request $request)
    {
        $preferredLanguage = $request->header('Accept-Language');
        app()->setLocale($preferredLanguage);
    }



    public static function storeFile($file,$type) : string
    {
        $fileName = rand(100000, 999999) . time() . $file->getClientOriginalName();
        $path = $file->storeAs($type, $fileName,'cv');
        return $path;
    }


    public static function checkUserType (Model $model ,User $user) : bool
    {
        $check = $model->where('user_id',$user->id)->first();
        return $check ? true : false;
    }


    public static function pagnationResponse($model) : array
    {
        return [
            'current_page' => $model->currentPage(),
            'last_page' => $model->lastPage(),
            'total' => $model->total(),
            'per_page' => $model->perPage(),
        ];
    }

    /**
     * return true if update successfully
     *
     * @param [type] $file
     * @param [type] $model
     * @param string $attributename
     * @param string $diskstore
     * @param string $diskdelete
     * @return boolean
     */
    public static function deleteFiles($file, $model,string $attributename,string $diskstore, string $diskdelete) : bool
    {
        if($file){
            $old = $model->$attributename;
            $path = self::storeFile($file,$diskstore);
            $update = $model->update([$attributename => $path]);
            if ($old) {
                Storage::disk($diskdelete)->delete($old);
            }
            return true ;
        }
        return false;
    }

}
?>
