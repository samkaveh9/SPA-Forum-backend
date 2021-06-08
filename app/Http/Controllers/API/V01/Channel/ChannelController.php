<?php

namespace App\Http\Controllers\API\V01\Channel;

use App\Channel;
use App\Http\Controllers\Controller;
use App\Http\Repositories\ChannelRepository;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChannelController extends Controller
{

    public function getAllChannelsList()
    {
        $channels = resolve(ChannelRepository::class)->all();
        return response()->json($channels, Response::HTTP_OK);
    }

    public function createNewChannel(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);

        resolve(ChannelRepository::class)->create($request);

        return response()->json([
            'message' => 'Channel created successfuly'
        ], Response::HTTP_CREATED);
    }

    public function updateChannel(Request $request)
    {
        $request->validate([
            'name' => ['required']
        ]);

        resolve(ChannelRepository::class)->update($request->id, $request->name);

        return response()->json([
            'message' => 'Channel edited successfuly'
        ], Response::HTTP_OK);
    }

    public function deleteChannel(Request $request)
    {
        $request->validate([
            'id' => ['required']
        ]);

        resolve(ChannelRepository::class)->delete($request->id);
        return response()->json([
            'message' => 'Channel deleted successfuly'
        ], Response::HTTP_OK);
    }
}
