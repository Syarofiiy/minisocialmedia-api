<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostsController extends Controller
{
    public function index() {

        $posts = Post::with(['user', 'comments', 'likes'])->get();

        return response()->json([
            'success' => true,
            'data' => $posts
        ]);
    }

    public function store(Request $request) {
        $user = JWTAuth::parseToken()->authenticate();

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'image_url' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 400);
        }

        $post = Post::create([
            'user_id' => $user->id,
            'content' => $request->get('content'),
            'image_url' => $request->image_url,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil membuat post baru.',
            'data' => $post
        ], 201);
    }

    public function show($id) {
        $post = Post::find($id);

        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    public function update($id, Request $request) {
        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
            'image_url' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                 'message' => $validator->errors()
            ], 400);
        }

        $post = Post::find($id);

        $post->content = $request->get('content');
        $post->image_url = $request->image_url;

        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil update data',
            'data' => $post
        ]);
    }

    public function destroy(int $id) {

        Post::destroy($id);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus'
        ]);
    }
}
