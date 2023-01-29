@extends('layouts.app')

@section('title','Edit Book')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Edit Book</h1>
</div>
<div class="row">
    <div class="col">
        <form method="post" action="{{route('books.update',['book'=>$book['data']['id']])}}">
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
                <label for="author">Author :</label>
                <select name="author" class="form-control" id="author">
                    <option value="">Select Author</option>
                    @foreach($authors['data'] as $author)
                        <option value="{{$author['id']}}" {{ $author['id'] == $book['data']['authorId'] ? 'selected':'' }} >{{$author['name']}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="title">Title :</label>
                <input type="text" name="title" class="form-control" id="title" value="{{$book['data']['title']}}">
            </div>
            <div class="mb-3">
                <label for="isbn">ISBN :</label>
                <input type="text" name="isbn" class="form-control" id="isbn" value="{{$book['data']['isbn']}}">
            </div>
            <div class="mb-3">
                <label for="description">Description :</label>
                <textarea name="description" class="form-control" id="description" rows="5">{{$book['data']['description']}}</textarea>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</div>
@endsection