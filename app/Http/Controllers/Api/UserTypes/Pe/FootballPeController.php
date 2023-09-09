<?php

namespace App\Http\Controllers\Api\UserTypes\Pe;

use App\Models\FootballPe;
use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class FootballPeController extends Controller
{
    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    public function storeFootballPe(Request $request ) :JsonResponse
    {
        $user = $request->user();
        // $checkType = HelpersFunctions::checkUserType(new FootballPe , $user);
        // if ($checkType) {
        //     return $this->finalResponse('failed',400,null,null,'you already pe');
        // }

        $data = $request->validate(FootballPe::rules());
        $file = $request->file('cv');
        $path = HelpersFunctions::storeFile($file,'cv/pe');
        $create = FootballPe::create([
            'user_id' => $user->id,
            'country'=> $data['country'],
            'city'=> $data['city'],
            'age'=> $data['age'],
            'university'=> $data['university'],
            'college' => $data['college'],
            'gender'=> $data['gender'],
            'phone_number'=> $data['phone_number'],
            'whatsapp_number'=> $data['whatsapp_number'],
            // 'possibility_travel'=> $data['possibility_travel'],
            'cv' => $path
        ]);

        $user->role = 'pe';
        $user->save();

        if($create)
        {
            return $this->finalResponse('success',200,'data created successfully');
        }

        return $this->finalResponse('failed',500,null,null,'some thing happen in server');

    }


    public function updateFootballPe(Request $request) : JsonResponse
    {
        $user = $request->user();
        $pe = $user->pe;
        $data = $request->validate(FootballPe::rulesUpdate($pe->id));
        try {
            $pe->update([
                'country' => $data['country'],
                'city' => $data['city'],
                'age' => $data['age'],
                'university' => $data['university'],
                'college' => $data['college'],
                'gender' => $data['gender'],
                'phone_number' => $data['phone_number'],
                'whatsapp_number' => $data['whatsapp_number'],
            ]);

            $file = $request->file('cv');
                if ($file) {
                    $oldFile = $pe->cv;
                    $path = HelpersFunctions::storeFile($file, 'cv/pe');
                    $pe->update(['cv' => $path]);
                    Storage::disk('cv')->delete($oldFile);
                }
            return $this->finalResponse('success',200,'data updated successfully');
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'some thing happen in server'.$th->getMessage());
        }
    }
}
