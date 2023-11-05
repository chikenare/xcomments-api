<?php

namespace App\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

interface CommentI
{
    public function getComments(string $id);
    public function storeComment(Request $request);
    public function updateComment(string $id, array $comment);
    public function destroyComment(string $id);
    public function storeFile(UploadedFile $file);

    //Reactions
    public function storeReaction(string $commentId, string $reaction);
}
