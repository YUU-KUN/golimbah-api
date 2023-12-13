<?php

namespace App\Http\Controllers;

use App\Models\Leaderboard;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Auth;

class LeaderboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = $request->limit ?? null;
        $mode = $request->mode;
        $leaderboard = Leaderboard::whereHas('GameSession', function($query) use ($mode) {
            $query->whereHas('UserGameSession')
            ->where('mode', $mode)
            ->where('status', 'finished')
            ->where('session_code', NULL)
            ->orderBy('score', 'desc');
        })->orderBy('score', 'desc')->limit($limit)->get()->unique('user_id');

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data public leaderboard',
            'data' => $leaderboard->load('User:id,fullname,photo')
        ], 200);
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
     * @param  \App\Models\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function show($game_session_id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function edit(Leaderboard $leaderboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Leaderboard $leaderboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Leaderboard  $leaderboard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Leaderboard $leaderboard)
    {
        //
    }

    public function getGuestLeaderboard($game_session_id) {
        $limit = $request->limit ?? null;
        $leaderboard = Leaderboard::where('game_session_id', $game_session_id)->orderBy('score', 'desc')->limit($limit)->get();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data guest leaderboard',
            'data' => $leaderboard->load('User:id,fullname,photo')
        ], 200);
    }
}
