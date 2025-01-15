<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Responses\CustomJsonResponse;
use App\Models\Comment;
use App\Models\Thread;
use App\Models\UserCommentLike;

class UserCommentLikeController extends Controller
{
    public function
    like(string $threadId, string $commentId)
    {
        $thread = Thread::find($threadId);
        if (!$thread) {
            return CustomJsonResponse::notFound('Thread not found');
        }

        $comment = Comment::find($commentId);
        if (!$comment) {
            return CustomJsonResponse::notFound('Comment not found');
        }

        $like = UserCommentLike::where('user_id', auth('api')->user()->id)
            ->where('comment_id', $comment->id)
            ->first();

        if ($like) {
            $like->delete();
            return CustomJsonResponse::success('Comment unliked successfully');
        }

        UserCommentLike::create([
            'user_id' => auth('api')->user()->id,
            'comment_id' => $comment->id
        ]);

        return CustomJsonResponse::success('Comment liked successfully', null);
    }
}
