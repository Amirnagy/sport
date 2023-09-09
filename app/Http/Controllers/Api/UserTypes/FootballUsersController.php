<?php

namespace App\Http\Controllers\Api\UserTypes;

use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use Illuminate\Database\Eloquent\Model;

class FootballUsersController extends Controller
{
    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    public function index(Request $request)
    {
        $user = $request->user();

        $role = $user->role;
        try {
            if($role == 'no_role')
        {
            return $this->finalResponse('failed',400,null,null,"you didn't compolete his profile , please complete it");
        }
        switch ($role) {
            case 'player':
                $profile = $user->player;
                break;
            case 'coach':
                $profile = $user->coach;
                break;
            case 'pe':
                $profile = $user->pe;

                break;
            default :

        }
        $request->merge(['role'=>$role]);

        return $this->finalResponse('seccess',200,["typeProfile"=>$role, "profile" => new ProfileResource($profile)]);

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'something failed in server'.$th->getMessage());

        }

    }
}
