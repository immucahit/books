@extends('layouts.app')

@section('title','Edit Author')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Edit Author</h1>
      </div>
<div class="row">
    <div class="col">
        <form method="post" action="{{route('authors.update',['author'=>$author['id']])}}">
            @csrf
            @method('put')
        @if ($errors->any())
        <div class="mb-3">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                  </div>
                @endif
                <div class="mb-3">
                <label for="name">Name :</label>
                <input type="text" name="name" class="form-control" id="name" value="{{$author['name']}}">
</div>
<div class="mb-3">
    <button type="submit" class="btn btn-primary">Save</button>
</div>
</form>
    </div>
</div>
@endsection