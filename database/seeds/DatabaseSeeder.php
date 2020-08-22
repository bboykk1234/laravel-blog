<?php

use App\Models\Article;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = factory(User::class, 10)->create();
        foreach ($users as $user) {
            if ($user->type === 1) {
                $articles = factory(Article::class, 5)->create(['user_id' => $user->id]);
                foreach (range(0, 10) as $key => $value) {
                    factory(Comment::class)->create(['user_id' => Arr::random($users->all())->id, 'article_id' => Arr::random($articles->all())->id]);
                }
            }
        }

    }
}
