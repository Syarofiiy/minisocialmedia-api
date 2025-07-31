<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentsController extends Controller
{
    public function store(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'post_id' => 'required',
            'content' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => true,
                'message' => $validator->errors()
            ]);
        }

        $comment = Comment::create([
            'user_id' => $user->id,
            'post_id' => $request->post_id,
            'content' => $request->get('content')   ,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil komentar',
            'data' => $comment
        ], 201);
    }

    public function destroy(int $id) {
        Comment::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus'
        ]);
    }
}
