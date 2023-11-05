<?php

namespace App\Http\Controllers;

use App\Repositories\CommentRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    private $model;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->model = $commentRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'commentable_id' => 'required',
            'body' => 'string',
            'file' => 'image'
        ]);

        try {
            $comment = $this->model->storeComment($request);
            return response()->json(['data' => $comment], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $comments = $this->model->getComments($id);
            return response()->json($comments);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'body' => 'required|string'
        ]);

        try {
            $comment = $this->model->updateComment($id, ['body' => $request->body]);
            return response()->json(['data' => $comment]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $this->model->destroyComment($id);
            return response()->json(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function storeReaction(Request $request)
    {
        $request->validate(['comment_id' => 'required', 'reaction' => 'required']);
        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
