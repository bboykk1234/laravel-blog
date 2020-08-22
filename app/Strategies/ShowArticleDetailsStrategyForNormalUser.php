<?php

namespace App\Strategies;

use App\Contracts\ShowArticleDetailsStrategyForDiffRole;
use App\Models\Article;
use App\Models\User;

class ShowArticleDetailsStrategyForNormalUser implements ShowArticleDetailsStrategyForDiffRole
{
    /**
     * @var User
     */
    protected $user;

    public function __construct(?User $user)
    {
        $this->user = $user;
    }

    public function load(array $options = [])
    {
        return Article::with(['comments' => function($query) {
            $query->orderBy('id', 'desc');
            $query->with('user');
        }]);
    }
}
