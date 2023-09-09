<?php

namespace App\Http\Controllers\Api\UserTypes\Coach;

use Illuminate\Http\Request;
use App\Models\FootballCoach;
use App\Helpers\HelpersFunctions;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class FootballCoachController extends Controller
{
    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    public function storeFootballCoach(Request $request ) :JsonResponse
    {
        // check user type on table
        $user = $request->user();
        // $checkType = HelpersFunctions::checkUserType(new FootballCoach , $user);
        // if ($checkType) {
        //     return $this->finalResponse('failed',400,null,null,'you already Coach');
        // }
        // vailate data profile
        $data = $request->validate(FootballCoach::rules());

        // store data_file in server
        $file = $request->file('cv');
        $path = HelpersFunctions::storeFile($file,'cv/coach');

        $create = FootballCoach::create([
            'user_id' => $user->id,
            'country'=> $data['country'],
            'city'=> $data['city'],
            'age'=> $data['age'],
            'jop_title' => $data['jop_title'],
            'gender'=> $data['gender'],
            'phone_number'=> $data['phone_number'],
            'whatsapp_number'=> $data['whatsapp_number'],
            // 'possibility_travel'=> $data['possibility_travel'],
            'cv' => $path
        ]);
        $user->role = 'coach';
        $user->save();
        if($create)
        {
            return $this->finalResponse('success',200,'data created successfully');
        }
        return $this->finalResponse('failed',500,null,null,'something failed in server');

    }

    public function updateFootballCoach(Request $request): JsonResponse
    {
        $user = $request->user();
        $coach = $user->coach;
        $data = $request->validate(FootballCoach::rulesUpdate($coach->id));
        try {
                $coach->update([
                    'country' => $data['country'],
                    'city' => $data['city'],
                    'age' => $data['age'],
                    'jop_title' => $data['jop_title'],
                    'gender' => $data['gender'],
                    'phone_number' => $data['phone_number'],
                    'whatsapp_number' => $data['whatsapp_number'],
                ]);

                $file = $request->file('cv');
                    if ($file) {
                        $oldFile = $coach->cv;
                        $path = HelpersFunctions::storeFile($file, 'cv/coach');
                        $coach->update(['cv' => $path]);
                        Storage::disk('cv')->delete($oldFile);
                    }
                return $this->finalResponse('success', 200, 'Data updated successfully', null);

        }catch (\Exception $e){
            return $this->finalResponse('error', 500, null,null,'An error occurred while updating data'. $e->getMessage());
        }
    }
}
