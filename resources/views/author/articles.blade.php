@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-10">
        <articles-table :user="{{ Auth::user() }}"></articles-table>
      </div>
    </div>
  </div>
@endsection
