<?php

namespace App\Http\Controllers;

use App\Models\ArticleUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ArticleUserController extends Controller
{
    public function index($name)
    {
        return inertia('ArticleUser/Index', [
            'user' => User::query()
                ->with(['articleFavorites.article' => function ($query) {
                    $query->with(['user','tags']);
                    $query->withCount(['users','comments']);
                },])
                ->whereName($name)
                ->firstOrFail(),
        ]);
    }

    public function store()
    {
        ArticleUser::firstOrCreate([
            'article_id' => request('article_id'),
            'user_id' => Auth::user()->getKey(),
        ]);
        return redirect()->back();
    }

    public function destroy()
    {
        ArticleUser::query()->where([
            'article_id' => request('article_id'),
            'user_id' => Auth::user()->getKey(),
        ])->firstOrFail()->deleteOrFail();
        return redirect()->back();
    }
}
