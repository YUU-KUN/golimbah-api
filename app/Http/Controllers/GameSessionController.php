<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGameSession;
use App\Models\Leaderboard;
use Auth;
use App\Events\Pusher;

class GameSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->role == 'admin') {
            $gameSessions = GameSession::all();
        } else {
            $gameSessions = UserGameSession::where('user_id', Auth::user()->id)->with('GameSession')->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data sesi game',
            'data' => $gameSessions
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
        $input = $request->all();

        $validator = \Validator::make($input, [
            'mode' => 'required|string|in:easy,hard',
            'time' => 'nullable|integer'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }

        $user = Auth::user();
        if ($user->role == 'admin') {
            $input['session_code'] = rand(000000, 999999);
            $input['status'] = 'pending';
        } else {
            $old_user_game = UserGameSession::where('user_id', $user->id)->first();
            $input['goal_score'] = $old_user_game ? $old_user_game->GameSession->goal_score += 50 : 50;
            $input['level'] = $old_user_game ? $old_user_game->GameSession->level += 1 : 1;
            $input['status'] = 'started';
        }

        $gameSession = GameSession::create($input);
        $gameSession->creator_id = Auth::user()->id;

        if ($user->role == 'user') {
            UserGameSession::create([
                'game_session_id' => $gameSession->id,
                'user_id' => $user->id,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat sesi permainan',
            'data' => $gameSession
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GameSession  $gameSession
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gameSession = GameSession::find($id);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan detail game',
            'data' => $gameSession->load('UserGameSession.User')
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GameSession  $gameSession
     * @return \Illuminate\Http\Response
     */
    public function edit(GameSession $gameSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GameSession  $gameSession
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GameSession $gameSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GameSession  $gameSession
     * @return \Illuminate\Http\Response
     */
    public function destroy(GameSession $gameSession)
    {
        $gameSession->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus sesi permainan',
            'data' => $gameSession
        ], 200);
    }

    public function startGame(Request $request) {
        $game_session_id = $request->game_session_id;
        $gameSession = GameSession::find($game_session_id);
        if ($gameSession->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat memulai permainan ini',
                'data' => NULL
            ], 401);
        };
        $gameSession->update([
            'status' => 'started'
        ]);
        event(new Pusher('game-session-started'));
        return response()->json([
            'success' => true,
            'message' => 'Berhasil memulai sesi permainan',
            'data' => $gameSession
        ], 200);
    }

    public function searchGameSession(Request $request) {
        $gameSession = GameSession::where('session_code', $request->session_code)->first();
        if (!$gameSession) {
            return response()->json([
                'success' => true,
                'message' => 'Sesi permainan tidak tersedia',
                'data' => NULL
            ], 404);
        }
        return response()->json([
            'success' => true,
            'message' => 'Sesi permainan tersedia',
            'data' => $gameSession
        ], 200);
        
    }

    public function joinGame(Request $request) {
        $input = $request->all();
        $validator = \Validator::make($input, [
            'session_code' => 'required|numeric|exists:game_sessions,session_code',
            'fullname' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }
        $input['user_id'] = User::create([
            'fullname' => $input['fullname'],
            'role' => 'guest',
        ])->id;
        $gameSession = GameSession::where('session_code', $input['session_code'])->first();
        if ($gameSession->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat bergabung pada permainan ini',
                'data' => NULL
            ], 401);
        }
        $input['game_session_id'] = $gameSession->id;

        $userGameSession = UserGameSession::create($input);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil bergabung pada permainan',
            'data' => $userGameSession->load('User:id,fullname')
        ], 200);
    }

    public function finishGame(Request $request) {
        $input = $request->all();
        $validator = \Validator::make($input, [
            'game_session_id' => 'required|string|exists:game_sessions,id',
            'user_id' => 'required|string|exists:user_game_sessions,user_id',
            'score' => 'required|integer',
            // game_session_id & user_id must be unique
            // 'game_session_id' => 'unique:leaderboards,game_session_id,NULL,id,user_id,'.$input['user_id'],
        ], [
            'user_id.exists' => 'Anda belum bergabung pada permainan ini',
            // 'game_session_id.unique' => 'Permainan sudah selesai'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }

        $gameSession = GameSession::find($input['game_session_id']);
        if ($gameSession->status == 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Permainan belum dimulai',
                'data' => NULL
            ], 401);
        }

        $gameSession->update([
            'status' => 'finished'
        ]);
        // accumulate score from previous gameplay
        $userGameSession = GameSession::where('user_id', $input['user_id'])->get();
        if ($userGameSession->count() > 0) {
            $input['score'] += $userGameSession->sum('score');
        }
        
        $leaderboard = Leaderboard::create($input);
        event(new Pusher('game-session-finished'));
        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat leaderboard permainan',
            'data' => $leaderboard
        ], 200);
    }

    public function getParticipants(Request $request) {
        $participants = UserGameSession::where('game_session_id', $request->game_session_id)->get()->load('User:id,fullname')->pluck('User');
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data partisipan',
            'data' => $participants
        ], 200);
    }
}
