<?php

namespace App\Http\Controllers;

use App\Models\UserGameSession;
use Illuminate\Http\Request;

class UserGameSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UserGameSession  $userGameSession
     * @return \Illuminate\Http\Response
     */
    public function show($game_session_id)
    {
        $userGameSession = UserGameSession::where('game_session_id', $game_session_id)->first();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan detail game',
            'data' => $userGameSession->load(['GameSession', 'User'])
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UserGameSession  $userGameSession
     * @return \Illuminate\Http\Response
     */
    public function edit(UserGameSession $userGameSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserGameSession  $userGameSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserGameSession $userGameSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UserGameSession  $userGameSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserGameSession $userGameSession)
    {
        //
    }
}
