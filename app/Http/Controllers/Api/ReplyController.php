<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReplyRequest;
use App\Http\Resources\Api\ReplyResource;
use App\Http\Responses\CustomJsonResponse;
use App\Models\Reply;
use App\Models\Thread;

class ReplyController extends Controller
{

    public function store(ReplyRequest $request, string $threadId, string $commentId)
    {
        $thread = Thread::find($threadId);

        if (!$thread) {
            return CustomJsonResponse::notFound('Thread not found');
        }

        $comment = $thread->comments()->find($commentId);

        if (!$comment) {
            return CustomJsonResponse::notFound('Comment not found');
        }

        $reply =  Reply::create(array_merge(
            $request->validated(),
            [
                'comment_id' => $comment->id,
                'user_id' => auth('api')->user()->id
            ]
        ));

        return CustomJsonResponse::success(
            'Comment created successfully',
            ['addedReply' => new ReplyResource($reply)],
            201
        );
    }

    public function destroy(string $threadId, string $commentId, string $replyId)
    {
        $thread = Thread::find($threadId);

        if (!$thread) {
            return CustomJsonResponse::notFound('Thread not found');
        }

        $comment = $thread->comments()->find($commentId);

        if (!$comment) {
            return CustomJsonResponse::notFound('Comment not found');
        }

        $reply = $comment->replies()->find($replyId);
        if (!$reply) {
            return CustomJsonResponse::notFound('Reply not found');
        }

        if ($reply->user_id !== auth('api')->user()->id) {
            return CustomJsonResponse::fail('Unauthorized', null, 403);
        }

        $reply->delete();
        return CustomJsonResponse::success('Reply deleted successfully');
    }
}
