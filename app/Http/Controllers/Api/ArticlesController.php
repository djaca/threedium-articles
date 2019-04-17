<?php

namespace App\Http\Controllers\Api;

use App\Article;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ArticlesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'body'  => 'required'
        ]);

        $request->user()->articles()->create($request->only(['title', 'body']));

        return response()->json([
            'status' => 'success',
            'message' => 'Article created successfully'
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $request->validate([
            'title' => 'required',
            'body'  => 'required'
        ]);

        $article->update($request->only(['title', 'body']));

        return response()->json([
            'status'  => 'success',
            'message' => 'Article updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Article $article
     *
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Article $article)
    {
        $this->authorize('update', $article);

        $article->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Article deleted successfully'
        ]);
    }
}
