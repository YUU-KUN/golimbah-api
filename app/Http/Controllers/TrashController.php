<?php

namespace App\Http\Controllers;

use App\Models\Trash;
use Illuminate\Http\Request;
use Storage;

class TrashController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        
        $game_mode = $request->game_mode;
        if ($game_mode == 'easy') {
            $trash = Trash::where('game_mode', 'easy')->get();
        } else {
            $easy_trash = Trash::where('game_mode', 'easy')->get();

            $trash = Trash::query();
            $hard_trash = Trash::where('game_mode', 'hard')->get();
            $trash = $hard_trash->merge($easy_trash);
            $trash = $trash->unique('label');
            $trash = $trash->values();
        }

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data semua sampah',
            'data' => $trash
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
            'name' => 'required',
            'description' => 'nullable',
            'category' => 'required|in:organic,inorganic,plastic,paper,glass,residue',
            'photo' => 'required|mimes:png,jpg,jpeg',
            'game_mode' => 'required|in:easy,hard'
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }

        if ($request->file('photo')) {
            $photo_filename = time().'.'.$request->photo->extension();
            // $photo_path = Storage::url('trashes/');
            $photo_path = '/trashes/';
            $request->photo->move(public_path($photo_path), $photo_filename);
            $input['photo'] = $photo_filename;
        }

        $trash = Trash::create($input);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menambahkan data sampah',
            'data' => $trash
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trash  $trash
     * @return \Illuminate\Http\Response
     */
    public function show(Trash $trash)
    {
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan data sampah',
            'data' => $trash
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Trash  $trash
     * @return \Illuminate\Http\Response
     */
    public function edit(Trash $trash)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trash  $trash
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trash $trash)
    {
        $validator = \Validator::make($input, [
            'name' => 'required',
            'description' => 'nullable',
            'category' => 'required|in:organic,inorganic,plastic,paper,glass,residue',
            'photo' => 'required|mimes:png,jpg,jpeg',
            'game_mode' => 'required|in:easy,hard'
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        if (isset($request->photo)) {
            // remove old photo
            if ($trash->photo) {
                $photo_path = Storage::url('trashes/');
                $old_photo = public_path($photo_path.$trash->photo);
                if (file_exists($old_photo)) {
                    unlink($old_photo);
                }
            }
            $photo_filename = time().'.'.$request->photo->extension();
            $photo_path = Storage::url('trashes/');
            $request->photo->move(public_path($photo_path), $photo_filename);
            $input['photo'] = $photo_filename;
        }
        
        $trash->update($input);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil mengubah data sampah',
            'data' => $trash
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trash  $trash
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trash $trash)
    {
        $trash->delete();
        return response()->json([
            'success' => true,
            'message' => 'Berhasil menghapus data sampah',
            'data' => $trash
        ], 200);
    }

    function getRandomTrashByGameMode(Request $request) {
        $game_mode = GameSession::find($request->game_session_id)->mode;
        $random_trash = Trash::where('game_mode', $game_mode)->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil mendapatkan sampah untuk permainan',
            'data' => $random_trash
        ], 200);
    }
}
