<?php

namespace App\Strategies;

use App\Contracts\GetCommentStrategyForDiffRole;
use App\Models\Comment;
use App\Models\User;

class GetCommentStrategyForAdmin implements GetCommentStrategyForDiffRole
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
        return Comment::query();
    }
}
