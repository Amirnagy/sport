<?php

namespace App\Http\Controllers\Api\UserTypes\Player;


use Illuminate\Http\Request;
use App\Models\FootballPlayer;
use App\Helpers\HelpersFunctions;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;


class FootballPlayerController extends Controller
{
    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    public function storeFootballPlayer(Request $request) :JsonResponse
    {
        // check user type on table
        $user = $request->user();
        // $checkType = HelpersFunctions::checkUserType(new FootballPlayer , $user);
        // if ($checkType) {
        //     return $this->finalResponse('failed',400,null,null,'you already player');
        // }
        // vailate data profile
        $data = $request->validate(FootballPlayer::rules());

        // store data_file in server
        $file = $request->file('cv');
        $path = HelpersFunctions::storeFile($file,'cv/player');

        $create = FootballPlayer::create([
            'user_id' => $user->id,
            'country'=> $data['country'],
            'city'=> $data['city'],
            'age'=> $data['age'],
            'weight'=> $data['weight'],
            'height'=> $data['height'],
            'gender'=> $data['gender'],
            'skills_level'=> $data['skills_level'],
            'foot_dominant'=> $data['foot_dominant'],
            'main_position'=> $data['main_position'],
            'phone_number'=> $data['phone_number'],
            'whatsapp_number'=> $data['whatsapp_number'],
            // 'possibility_travel'=> $data['possibility_travel'],
            'cv' => $path
        ]);
        $user->role = 'player';
        $user->save();
        if($create && $path)
        {
            return $this->finalResponse('success',200,'data created successfully');
        }

        return $this->finalResponse('failed',500,null,null,'something failed in server');

    }


    public function updateFootballPlayer(Request $request) : JsonResponse
    {
        $user = $request->user();
        $player = $user->player;
        $data = $request->validate(FootballPlayer::rulesUpdate($player->id));
        try {
                $player->update([
                    'country'=> $data['country'],
                    'city'=> $data['city'],
                    'age'=> $data['age'],
                    'weight'=> $data['weight'],
                    'height'=> $data['height'],
                    'gender'=> $data['gender'],
                    'skills_level'=> $data['skills_level'],
                    'foot_dominant'=> $data['foot_dominant'],
                    'main_position'=> $data['main_position'],
                    'phone_number'=> $data['phone_number'],
                    'whatsapp_number'=> $data['whatsapp_number'],
                ]);
                $file = $request->file('cv');
                    if ($file) {
                        $oldFile = $player->cv;
                        $path = HelpersFunctions::storeFile($file, 'cv/player');
                        $player->update(['cv' => $path]);
                        Storage::disk('cv')->delete($oldFile);
                    }
                return $this->finalResponse('success', 200, 'Data updated successfully', null);

        }catch (\Exception $e){
            return $this->finalResponse('error', 500, 'An error occurred while updating data', $e->getMessage());
        }

    }

}
