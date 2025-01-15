<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ThreadRequest;
use App\Http\Resources\Api\ThreadResource;
use App\Http\Responses\CustomJsonResponse;
use App\Models\Thread;

class ThreadController extends Controller
{
    public function index()
    {
        $threads = Thread::with('user')->latest()->get();

        return CustomJsonResponse::success(
            'Threads retrieved successfully',
            ['threads' => ThreadResource::collection($threads),]
        );
    }

    public function store(ThreadRequest $request)
    {
        $thread = Thread::create(array_merge(
            $request->validated(),
            ['user_id' => auth('api')->user()->id]
        ));

        return CustomJsonResponse::success(
            'Thread created successfully',
            ['addedThread' => new ThreadResource($thread),],
            201
        );
    }

    public function show(string $threadId)
    {
        $thread = Thread::with('comments')->find($threadId);
        if (!$thread) {
            return CustomJsonResponse::notFound('Thread not found');
        }

        return CustomJsonResponse::success(
            'Thread retrieved successfully',
            ['thread' => new ThreadResource($thread),]
        );
    }
}
