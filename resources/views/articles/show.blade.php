@extends('layouts.app')

@section('content')
  <div class="jumbotron jumbotron-fluid" style="background-image: url('{{ $article->image }}');background-repeat: no-repeat;background-position: center top;min-height: 30rem;background-color: transparent">
  </div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <div class="card-body">
          <h1>{{ $article->title }}</h1>

          <div class="mt-4">{!! $article->body !!}</div>
        </div>
      </div>
    </div>
  </div>
@endsection
