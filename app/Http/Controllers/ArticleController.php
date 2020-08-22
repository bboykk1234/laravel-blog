<?php

namespace App\Http\Controllers;

use App\Contracts\ListArticleStrategyForDiffRole;
use App\Contracts\ShowArticleDetailsStrategyForDiffRole;
use App\Http\Requests\ArticleWrite;
use App\Http\Resources\Article as ArticleResource;
use App\Http\Resources\ArticleCollection;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ListArticleStrategyForDiffRole $loader)
    {
        return new ArticleCollection($loader->load()->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ArticleWrite  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleWrite $request)
    {
        $article = Article::create([
            'user_id' => $request->user()->id,
            'title' => $request->input('data.title'),
            'description' => $request->input('data.description'),
        ]);

        return response()->json([
            'data' => $article->toArray(),
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ShowArticleDetailsStrategyForDiffRole $loader, $id)
    {
        $article = $loader->load()->where('id', $id)->first();

        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ArticleWrite  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleWrite $request, $id)
    {
        $article = Article::where('id', $id)->first();

        if ($article === null) {
            return response()->json([
                'errors' => [
                    'title' => 'Resource not found',
                ]
            ], Response::HTTP_NOT_FOUND);
        }

        $status = $article->fill([
            'title' => $request->input('data.title'),
            'description' => $request->input('data.description'),
        ])->save();

        return response()->json([
            'status' => $status,
            'data' => $article->toArray(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $req, $id)
    {
        return response()->json([
            'status' => (bool) Article::where('id', $id)->delete(),
        ]);
    }
}
