<?php

namespace App\Repositories;

use App\Interfaces\CommentI;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentRepository implements CommentI
{

    public function getComments(string $id)
    {
        return Comment::with('user:id,name,avatar')
            ->where('commentable_id', $id)
            ->latest()
            ->simplePaginate(10);
    }
    public function storeComment(Request $request)
    {
        $user = Auth::user();

        $isFile = $request->hasFile('file');
        $comment = $user->comments()->create([
            'commentable_id' => $request->commentable_id,
            'body' => $isFile ? $this->storeFile($request->file) : $request->body
        ]);
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
