<?php

namespace App\Http\Controllers\Api;

use App\Article;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = request()->has('author')
            ? User::findOrFail(request('author'))->articles()->orderBy('articles.created_at', 'desc')
            : Article::with('author')->orderBy('created_at', 'desc');

        $articles = $articles->simplePaginate(5)->appends(request()->query());

        return response()->json($articles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'required|unique:articles,title',
            'body'    => 'required',
            'excerpt' => 'required',
            'image'   => 'required|image'
        ]);

        $request->user()
                ->articles()
                ->create([
                    'title'   => $request->title,
                    'body'    => $request->body,
                    'excerpt' => $request->excerpt,
                    'image'   => $this->handleImage(request()->file('image'))
                ]);

        return response()->json([
            'status'  => 'success',
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

    private function handleImage($file)
    {
        $photo = Image::make($file->getRealPath())
                      ->fit(918, 400, function ($constraint) {
                          $constraint->upsize();
                      })
                      ->encode('jpg');

        Storage::put($name = $file->hashName(), $photo);

        return $name;
    }
}
