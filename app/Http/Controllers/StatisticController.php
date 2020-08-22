<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;

class StatisticController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => [
                'articles_count' => Article::count(),
                'comments_count' => Comment::count(),
                'users_count' => User::count(),
                'comments_count_per_user' => User::withCount('comments')->get(),
            ]
        ]);
    }
}
