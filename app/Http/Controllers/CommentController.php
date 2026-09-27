<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $attributes = $request->safe()->except('avatar');

        if ($request->hasFile('avatar')) {
            $attributes['avatar_path'] = $request->file('avatar')->store('comments', 'public');
        }

        Comment::create($attributes);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Comment published',
            ], 201);
        }

        return back()->with('success', 'Komentar Anda berhasil dipublikasikan. Terima kasih!');
    }
}
