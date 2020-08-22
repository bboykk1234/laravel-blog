<?php

namespace App\Http\Controllers;

use App\Contracts\GetCommentStrategyForDiffRole;
use App\Http\Requests\CommentPost;
use App\Http\Requests\CommentPut;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  CommentPost  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CommentPost $request)
    {
        $comment = Comment::create([
            'user_id' => $request->user()->id,
            'article_id' => $request->input('data.article_id'),
            'body' => $request->input('data.body'),
        ]);

        return response()->json([
            'data' => $comment->toArray(),
        ], Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CommentPut  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CommentPut $request, $id)
    {
        $comment = Comment::where('user_id', $request->user()->id)
            ->where('id', $id)->first();

        if ($comment === null) {
            return response()->json([
                'errors' => [
                    'title' => 'Resource not found',
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        $status = $comment->fill([
            'body' => $request->input('data.body'),
        ])->save();

        return response()->json([
            'status' => $status,
            'data' => $comment->toArray(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GetCommentStrategyForDiffRole $loader, $id)
    {
        return response()->json([
            'status' => (bool) $loader->load()->where('id', $id)->delete(),
        ]);
    }
}
