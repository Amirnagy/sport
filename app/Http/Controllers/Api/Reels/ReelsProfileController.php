<?php

namespace App\Http\Controllers\Api\Reels;

use App\Models\Reel;
use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReelsResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\DeletedReelsResource;

class ReelsProfileController extends Controller
{
    public function __construct(Request $request)
    {
        HelpersFunctions::setLang($request);
    }
    // create
    public function storeProfileReels(Request $request)
    {
        $user = $request->user('api');
        $data = $request->validate(Reel::rules());
        $file = $request->file('video');
        $cover = $request->file('cover');
        try {
            $videopath = HelpersFunctions::storeFile($file,'reels');
            if($cover)
            {
                $pathcover = HelpersFunctions::storeFile($cover,'reels/cover');
            }
            // create db reel

            $create = Reel::create(Reel::prepareReelData($user, $data, $videopath ,$pathcover ?? null));

            if($create && $videopath)
            {
                return $this->finalResponse('success',200,'data created successfully');
            }
        } catch (\Throwable $th) {
            return $this->finalResponse('error', 500,null,null, 'An error occurred while storing data' .$th->getMessage());
        }
    }

    public function updateProfileReel(Request $request)
    {
        $user = $request->user('api');
        // get data from requset
        $data = $request->validate(Reel::updateRules());
        $video = $request->file('video_path');
        $cover = $request->file('cover');
        // get reel
        $reel = Reel::UserReel($user->id,$request)->first();
        if(!$reel){
            return $this->finalResponse('failed',400,null,null,"can't update this element");
        }
        try {
            $reel->update(['description' => $data['description']]);

            $video = HelpersFunctions::deleteFiles($video,$reel,'video_path','reels','cv');
            if($video){
                $reel->update(['views' => 0,'likes' => 0]);
            }

            $cover = HelpersFunctions::deleteFiles($cover,$reel,'cover','reels/cover','cv');

                return $this->finalResponse('success',200,'data created successfully');

        } catch (\Throwable $th) {
            return $this->finalResponse('error', 500,null,null, 'An error occurred while storing data ' .$th->getMessage());
        }
    }



    // view all profile reels
    public function viewProfileReels(Request $request)
    {
        $user = $request->user('api');
        $perPage = $request->input('per_page', 9);
        try {
            $reels = Reel::users($user->id)->paginate($perPage);

            $pagination = HelpersFunctions::pagnationResponse($reels);

        return $reels->items() ?
            $this->finalResponse('success',200,ReelsResource::collection($reels->items()),$pagination) :
            $this->finalResponse('success',204,'no data found');

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server '.$th->getMessage());
        }

    }


    // delete one reel
    public function deleteProfileReels(Request $request)
    {
        $user = $request->user('api');
        $reel = Reel::UserReel($user->id, $request)->first();

        if(!$reel){
            return $this->finalResponse('failed',400,null,null,"can't delete this element");
        }
        $delete = $reel->delete();

        return $delete ? $this->finalResponse('success',200,'data deleted successfully',null,null) :
            $this->finalResponse('failed',400,null,null,"some thing wrong happen");
    }
    public function forceDeleteProfileReels(Request $request,$id)
    {
        $user = $request->user('api');
        $reel = Reel::UserReel($user->id,$request)->withTrashed()->first();
        if(!$reel){
            return $this->finalResponse('failed',400,null,null,"can't delete this element");
        }
        $delete = $reel->forceDelete();

        return $delete ? $this->finalResponse('success',200,'data deleted successfully',null,null) :
            $this->finalResponse('failed',400,null,null,"some thing wrong happen");
    }

    // view deleted
    public function ArchivedProfileReels(Request $request)
    {
        $user = $request->user('api');
        $perPage = $request->input('per_page', 9);
        try {
            $reels = Reel::users($user->id)->onlyTrashed()->paginate($perPage);

            $pagination = HelpersFunctions::pagnationResponse($reels);

        return $reels ?
            $this->finalResponse('success',200,DeletedReelsResource::collection($reels->items()),$pagination) :
            $this->finalResponse('success',204,'no data found');

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage());
        }
    }


}
