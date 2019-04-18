@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" action="{{route('categoryDelete', $category)}}">
                    @csrf
                    <input name="_method" type="hidden" value="DELETE">
                    <h3>Are you sure you want to delete category: <strong>{{$category->name}}</strong>?</h3>
                    <a class="mr-3" href="{{route('categoryList')}}">Back</a>
                    <button class="btn btn-primary">Yes</button>
                </form>
            </div>
        </div>
    </div>
@endsection
