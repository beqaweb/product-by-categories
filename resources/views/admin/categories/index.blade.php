@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div>
                    <a href="{{route('newCategoryForm')}}">
                        <button class="btn btn-primary">Add New</button>
                    </a>
                </div>

                @if(count($categories) > 0)
                    <ul class="list-group mt-3">
                        @foreach($categories as $category)
                            <li class="list-group-item row" style="display:flex;">
                                <span class="col-9">{{$category->name}}</span>
                                <span>
                            <a class="mr-2" href="{{route('categoryUpdateForm', $category)}}">Edit</a>
                            <a class="mr-2" href="{{route('categoryDeleteConfirm', $category)}}">Delete</a>
                            <a href="{{route('categoryPermissions', $category)}}">Permissions</a>
                        </span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-3 d-flex justify-content-center">{{ $categories->links() }}</div>
                @elseIf(count($categories) === 0)
                    <h3 class="text-center m-3">No categories added yet</h3>
                @endif
            </div>
        </div>
    </div>
@endsection
