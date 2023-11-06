<?php

namespace App\Repositories;

use App\Interfaces\CommentI;
use App\Models\Channel;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentRepository implements CommentI
{

    public function getComments(Request $request, string $channelName)
    {
        if ($request->has('reply')) {
            $comment = Comment::findOrFail($channelName);
            return $comment->replies()->with('user:id,name,avatar')
                ->latest()
                ->simplePaginate(10);
        }
        $channel = Channel::where('name', $channelName)->first();
        if (!$channel) $channel = Channel::create(['name' => $channelName]);

        return $channel
            ->comments()
            ->withCount('replies')
            ->with('user:id,name,avatar')
            ->latest()
            ->simplePaginate(10);
    }
    public function storeComment(Request $request)
    {
        $user = Auth::user();
        $isFile = $request->hasFile('file');

        $data = [
            'user_id' => $user->id,
            'body' => $isFile ? $this->storeFile($request->file) : $request->body
        ];

        if ($request->has('reply')) {
            $comment = Comment::findOrFail($request->channel);
            return $comment->replies()->create($data);
        }
        $channel = Channel::where('name', $request->channel)->firstOrFail();

        $comment = $channel->comments()->create($data);
        return $comment;
    }
    public function updateComment(string $id, array $comment)
    {
        $user = Auth::user();

        return $user->comments()->findOrFail($id)->update($comment);
    }
    public function destroyComment(string $id)
    {
        $user = Auth::user();
        return $user->comments()->findOrFail($id)->delete();
    }
    public function storeFile(UploadedFile $file): string
    {
        return Storage::disk('public')->put('files', $file);
    }

    public function storeReaction(string $commentId, string $reaction)
    {
        $comment = Comment::findOrFail($commentId);
        $comment->toggleReaction($reaction);
        return $comment;
    }
}
