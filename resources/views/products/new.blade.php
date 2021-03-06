@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(!$categories || count($categories) === 0)
                    @if(auth()->user()->hasRole('Super admin'))
                        <h3 class="h3">Please add at least one category.</h3>
                    @else
                        <h3 class="h3">You are not allowed to add any products for now, please ask Super Admin to assign
                            you at least one category.</h3>
                    @endif
                @else
                    <h3 class="h3 text-center">Add new product</h3>

                    <form method="POST" enctype="multipart/form-data" action="{{ route('newProduct') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                            <div class="col-md-6">
                                <input id="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                       name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="image" class="col-md-4 col-form-label text-md-right">Image</label>

                            <div class="col-md-6">
                                <input id="image" type="file"
                                       class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}" name="image"
                                       required>

                                @if ($errors->has('image'))
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right" for="categories">Categories</label>

                            <div class="col-md-6">
                                <select class="custom-select" name="category_id" id="categories">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">Create</button>
                            </div>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
