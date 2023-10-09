<?php

namespace App\Http\Controllers\Api\Reels\Comments;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\CommentSave;
use Illuminate\Http\Request;
use App\Models\CommentReport;
use App\Helpers\HelpersFunctions;
use App\Http\Controllers\Controller;

class CommentsController extends Controller
{
    public function storeComment(Request $request)
    {
        $request->merge(['idreel'=>$request->idreel]);
        $data = $request->validate(Comment::rules());
        $user = $request->user('api');
        $comment = Comment::create([
            'user_id' => $user->id,
            'reel_id' => $data['idreel'],
            'parent_id' => null,
            'content' => $data['content'],
        ]);
        return $this->finalResponse('success',200,$comment,null);
    }
    public function updateComment(Request $request)
    {
        $request->merge(['idreel'=>$request->idreel,'idcomment'=>$request->idcomment]);
        $data = $request->validate(Comment::updateRules());
        $user = $request->user('api');
        try {
            $comment = Comment::where('id',$data['idcomment'])->where('user_id',$user->id)->where('reel_id',$data['idreel'])->first();
            $comment->update(['content' => $data['content']]);
            return $this->finalResponse('success',200,$comment,null);
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'some thing happen in the server'. $th);
        }
    }
    public function deleteComment(Request $request)
    {
        $request->merge(['idreel'=>$request->idreel,'idcomment'=>$request->idcomment]);
        $data = $request->validate(
            ['idreel' => 'required|integer|exists:reels,id',
                'idcomment' => 'required|integer|exists:comments,id']);
        $user = $request->user('api');
        try {
            $comment = Comment::where('id',$data['idcomment'])->where('user_id',$user->id)->where('reel_id',$data['idreel'])->first();
            if(!$comment){
            return $this->finalResponse('failed',403 ,null,null,"can't delete this commen't");
            }
            $comment->delete();
            return $this->finalResponse('success',200,'comment deleted successfully',null);
        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'some thing happen in the server'. $th);
        }
    }

    public function likeComment(Request $request)
    {
        try {
                $user = $request->user('api');
                $request->merge(['reel_id'=>$request->idreel,'comment_id'=>$request->idcomment]);
                $request->validate(CommentLike::rule());
                    $check = CommentLike::where('comment_id' ,$request->comment_id)->where('reel_id',$request->reel_id)->where('user_id' , $user->id)->first();
                    if ($check) {
                        return $this->finalResponse('failed',403,null,null,'you aleady make like for item');
                    }
                $create = CommentLike::create([
                    'comment_id'=>$request->comment_id,
                    'reel_id'=>$request->reel_id,
                    'user_id'=>$user->id,
                    'like_type'=>$request->like_type]);
                $comment = Comment::where('reel_id',$request->reel_id)->where('id' , $request->comment_id)->first();

                $comment->likes +=1;
                $comment->save();
            return $this->finalResponse('succcess',200,'sucess make like');

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }
    public function unlikeComment(Request $request)
    {
        try {
            $user = $request->user('api');
            $request->merge(['reel_id'=>$request->idreel,'comment_id'=>$request->idcomment]);
            $request->validate(CommentLike::updaterule());
                $like = CommentLike::where('comment_id' ,$request->comment_id)->where('reel_id',$request->reel_id)->where('user_id' , $user->id)->first();
                if (!$like) {
                    return $this->finalResponse('failed',403,null,null,'cant make unlike');
                }
                $like->delete();
                $comment = Comment::where('reel_id',$request->reel_id)->where('id' , $request->comment_id)->first();
                $comment->likes -=1;
                $comment->save();
                return $this->finalResponse('succcess',200,'sucess delete like');
    } catch (\Throwable $th) {
        return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
    }
    }
    public function saveComment(Request $request)
    {
        try {
            $user = $request->user('api');
            $request->merge(['reel_id'=>$request->idreel,'comment_id'=>$request->idcomment]);
            $request->validate(CommentSave::rule());
            $check = CommentSave::where('comment_id' ,$request->comment_id)->where('reel_id',$request->reel_id)->where('user_id' , $user->id)->first();
            if ($check) {return $this->finalResponse('failed',403,null,null,'you aleady save item');}

            $create = CommentSave::create([
                'comment_id'=>$request->comment_id,
                'reel_id'=>$request->reel_id,
                'user_id'=>$user->id]);
        return $this->finalResponse('succcess',200,'sucess save comment');

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }
    public function unsaveComment(Request $request)
    {
        try {
            $user = $request->user('api');
            $request->merge(['reel_id'=>$request->idreel,'comment_id'=>$request->idcomment]);
            $request->validate(CommentSave::rule());
            $check = CommentSave::where('comment_id' ,$request->comment_id)->where('reel_id',$request->reel_id)->where('user_id' , $user->id)->first();
            if (!$check) {return $this->finalResponse('failed',403,null,null,"can't unsave item");}
            $check->delete();

        return $this->finalResponse('succcess',200,'sucess unsave comment');

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }
    public function reportComment(Request $request)
    {
        try {
            $user = $request->user('api');
            $request->merge(['reel_id'=>$request->idreel,'comment_id'=>$request->idcomment]);
            $request->validate(CommentReport::rule());

            $create = CommentReport::create([
                'comment_id'=>$request->comment_id,
                'reel_id'=>$request->reel_id,
                'user_id'=>$user->id,
                'report'=>$request->report,
            ]);
        return $this->finalResponse('succcess',200,'report has been sent we will Checks it');

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }
    public function showReplies(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 9);
            $user = $request->user('api');
            $request->merge(['reel_id'=>$request->idreel,'id'=>$request->idcomment]);
            $request->validate(Comment::replayRules());
            $replies = Comment::where('reel_id',$request->reel_id)->where('parent_id', $request->id)
            ->with(['user'=> function ($query) {
                $query->select('id','avatar','username')->get();
            }])

            ->paginate($perPage);
            if (!$replies) {return $this->finalResponse('success',204,'no comments to show ');}

            $pagination = HelpersFunctions::pagnationResponse($replies);
        return $this->finalResponse('succcess',200,$replies->items(),$pagination);

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }
    public function createReplies(Request $request)
    {
        try {
            $user = $request->user('api');
            $request->merge(['reel_id'=>$request->idreel,'id'=>$request->idcomment]);
            $data = $request->validate(Comment::createReplayRules());

            $comment = Comment::create([
                'user_id' => $user->id,
                'reel_id' => $data['reel_id'],
                'parent_id' => $data['id'],
                'content' => $data['content'],
            ]);
            if ($comment) {
                return $this->finalResponse('success',200,'comment created successfully');
            }

        } catch (\Throwable $th) {
            return $this->finalResponse('failed',500,null,null,'somthing happen in server'.$th->getMessage().'please contact to backeknd');
        }
    }

}
