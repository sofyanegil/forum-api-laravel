<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\Api\CommentResource;
use App\Http\Responses\CustomJsonResponse;
use App\Models\Comment;
use App\Models\Thread;

class CommentController extends Controller
{
    public function store(CommentRequest $request, string $threadId)
    {
        $thread = Thread::find($threadId);

        if (!$thread) {
            return CustomJsonResponse::notFound('Thread not found');
        }

        $comment =  Comment::create(array_merge(
            $request->validated(),
            [
                'thread_id' => $thread->id,
                'user_id' => auth('api')->user()->id
            ]
        ));

        return CustomJsonResponse::success(
            'Comment created successfully',
            ['addedComment' => new CommentResource($comment)],
            201
        );
    }

    public function destroy(string $threadId, string $commentId)
    {
        $thread = Thread::find($threadId);
        if (!$thread) {
            return CustomJsonResponse::notFound('Thread not found');
        }

        $comment = $thread->comments()->find($commentId);
        if (!$comment) {
            return CustomJsonResponse::notFound('Comment not found');
        }

        if ($comment->user_id !== auth('api')->user()->id) {
            return CustomJsonResponse::fail('Unauthorized', null, 403);
        }

        $comment->delete();

        return CustomJsonResponse::success('Comment deleted successfully');
    }
}
