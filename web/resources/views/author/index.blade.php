@extends('layouts.app')

@section('title','Authors')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Authors</h1>
      </div>
<div class="row mb-3">
    <div class="col">
        <a href="{{route('authors.create')}}" class="btn btn-success">New</a>
    </div>
</div>
<div class="row">
    <div class="col">
    <div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($authors['data'] as $author)
            <tr>
            <th scope="row">{{$author['id']}}</th>
            <td>{{$author['name']}}</td>
            <td>
                <div class="btn-group">
                    <a href="{{route('authors.edit',['author'=>$author['id']])}}" class="btn btn-sm btn-warning">Edit</a>
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