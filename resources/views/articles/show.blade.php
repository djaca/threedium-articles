@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card">
          <img src="{{ $article->image }}" class="card-img-top" alt="...">
          <div class="card-body">

            <div class="text-center">
              <h1 class="card-title display-4">{{ $article->title }}</h1>
              <h4 class="card-subtitle mb-3">
                {{ $article->excerpt }}
              </h4>

              <h5><a href="/articles?author={{ $article->author->id  }}">{{ $article->author->name }}</a> - {{ $article->created_at }}</h5>
            </div>

            <div class="card-text mt-5" style="font-size: 1.1rem">
              {!! $article->body !!}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
