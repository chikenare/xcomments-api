<?php

namespace App\Http\Controllers;

use App\Repositories\ChannelRepository;
use Exception;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
    private $model;

    public function __construct(ChannelRepository $channelRepository)
    {
        $this->model = $channelRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $channels = $this->model->getChannels();
            return response()->json(['data' => $channels]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
