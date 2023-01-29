@extends('layouts.app')

@section('title','Books')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Books</h1>
      </div>
<div class="row mb-3">
    <div class="col">
        <a href="{{route('books.create')}}" class="btn btn-success">New</a>
    </div>
</div>
<div class="row">
    <div class="col">
    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Author</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">ISBN</th>
            <th scope="col">Price</th>
            <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books['data'] as $book)
            <tr>
            <th scope="row">{{$book['id']}}</th>
            <td>
                @if($book['author'])
                    {{$book['author']['name']}}
                @endif
            </td>
            <td>{{$book['title']}}</td>
            <td>{{$book['description']}}</td>
            <td>{{$book['isbn']}}</td>
            <td>
                @if($book['price'])
                    {{number_format($book['price'],2,'.',',')}}
                @endif
            </td>
            <td>
                <div class="btn-group">
                    <a href="{{route('books.edit',['book'=>$book['id']])}}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{route('books.destroy',['book'=>$book['id']])}}" onclick="return confirm('Are you sure for delete?')" class="btn btn-sm btn-danger">Delete</a>
                </div>
            </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>
    </div>
</div>
@endsection