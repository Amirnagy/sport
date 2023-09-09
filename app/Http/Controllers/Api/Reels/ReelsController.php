<?php

namespace App\Http\Controllers\Api\Reels;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Helpers\HelpersFunctions;
use App\Http\Controllers\Controller;

class ReelsController extends Controller
{
    public function viewReels()
    {

    }
    public function likeReels()
    {

    }
    public function unlikeReels()
    {

    }
    public function saveReels()
    {

    }
    public function unsaveReels()
    {

    }
    public function shareReels()
    {

    }
    public function reportReel()
    {

    }
    // get comment for any reel
    public function viewComentOnReels(Request $request)
    {
        $request->merge(['id'=>$request->id]);
        $perPage = $request->input('per_page', 9);
        $id_reel = $request->id;
        try {
            $comments = Comment::where('reel_id' , $id_reel)->with(['user' =>function ($query){
                $query->select('id','avatar','username')->get();
            }])->paginate($perPage);
            $pagination = HelpersFunctions::pagnationResponse($comments);
        return $comments ? $this->finalResponse('succcess',200,$comments->items(),$pagination) :
            $this->finalResponse('failed',204,null,null,'no comment found');
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }

}
