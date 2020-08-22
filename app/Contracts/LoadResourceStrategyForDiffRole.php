<?php

namespace App\Contracts;

use App\Models\User;

interface LoadResourceStrategyForDiffRole
{
    public function __construct(?User $user);
    public function load(array $options = []);
}
