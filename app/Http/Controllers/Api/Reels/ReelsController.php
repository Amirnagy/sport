<?php

namespace App\Http\Controllers\Api\Reels;

use App\Http\Resources\ReelsResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use App\Http\Controllers\Controller;
use App\Models\Reel;
use App\Models\ReelLike;
use App\Models\ReelReport;
use App\Models\ReelSave;
use App\Models\ReelView;

class ReelsController extends Controller
{
    /**
     * increase view of Reel by +1
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewReel(Request $request)
    {
        $user = $request->user();
        $reelViewData = ReelView::prepareReelViewData($user, $request);
        ReelView::create($reelViewData);
        return $this->finalResponse('success',200,'view success');
    }


    /**
     * like Reels function
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likeReels(Request $request)
    {
        $request->validate(['like_type' => 'in:like,love']);
        try {
            $user = $request->user('api');
            $check = ReelLike::userOfReel($user,$request)->first();
            if ($check){ return $this->finalResponse('failed',403,null,null,'you aleady make like for item');}
            $data = ReelLike::prepareReelLikeData($user, $request);
            ReelLike::create($data);
            $reel = Reel::find($request->id);
            $reel->likes +=1;
            $reel->save();
            return  $this->finalResponse('succcess',200,'sucess make like') ;
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server '.
            $th->getMessage().' please contact to backeknd');
        }
    }



    /**
     * unlike Reels function
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlikeReels(Request $request)
    {
        try {
            $user = $request->user('api');
            $check = ReelLike::userOfReel($user, $request)->first();
            if (!$check){ return $this->finalResponse('failed',400,null,null,'item not liked');}
            $check->delete();
            $reel = Reel::find($request->id);
            $reel->likes -=1;
            $reel->save();
            return  $this->finalResponse('succcess',200,'sucess unlike');
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }

    public function saveReels(Request $request)
    {
        try {
            $user = $request->user('api');
                $check = ReelSave::userOfReel($user,$request)->first();
                if ($check) {
                    return $this->finalResponse('failed',400,'item already seved');
                }
                $data =  ReelSave::prepareReelSaveData($user,$request);
                ReelSave::create($data);
                return  $this->finalResponse('success',200,'save sucess');
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }
    public function unsaveReels(Request $request)
    {
        try {
            $user = $request->user('api');
                $check = ReelSave::userOfReel($user,$request)->first();
                if (!$check) {
                    return  $this->finalResponse('failed',400,'already unsaved');
                }
                $check->delete();
                return  $this->finalResponse('success',200,'unsave success');
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server '.$th->getMessage() . ' please contact to backeknd');
        }
    }
    public function shareReels(Request $request)
    {

    }

    public function reportReel(Request $request)
    {
        try {
            $user = $request->user('api');
            $data = ReelReport::prepareReelReportData($user,$request);
            ReelReport::create($data);
            return $this->finalResponse('succcess',200,'report has been sent we will Checks it');
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }
    // get comment for any reel
    public function viewComentOnReels(Request $request)
    {
        $perPage = $request->input('per_page', 9);
        try {
            $comments = Comment::where('reel_id' , $request->id)->with(['user' =>function ($query){
                $query->select('id','avatar','username')->get();
            }])->paginate($perPage);
            $pagination = HelpersFunctions::pagnationResponse($comments);
            return $comments->items() ? $this->finalResponse('succcess',200,$comments->items(),$pagination) :
            $this->finalResponse('failed',204,null,null,'no comment found');
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }

}
