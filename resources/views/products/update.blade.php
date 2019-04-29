@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form method="POST" enctype="multipart/form-data" action="{{ route('productUpdate', $product) }}">
                    <input name="_method" type="hidden" value="PUT">
                    @csrf

                    <div class="form-group row">
                        <label for="name" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
                            <input id="name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                   name="name" value="{{ $product->name }}" required>

                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-center">
                        <img style="width: 150px; height: 150px;object-fit: contain;" src="{{$product->image->url}}"
                             alt=""/>
                    </div>
                    <div class="form-group row">
                        <label for="image" class="col-md-4 col-form-label text-md-right">New image</label>

                        <div class="col-md-6">
                            <input id="image" type="file"
                                   class="form-control {{ $errors->has('image') ? ' is-invalid' : '' }}" name="image">

                            @if ($errors->has('image'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('image') }}</strong>
                                </span>
                            @endif
                            <small>the old one will be delete and replaced</small>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-4 col-form-label text-md-right" for="categories">Categories</label>

                        <div class="col-md-6">
                            <select class="custom-select" name="category_id" id="categories">
                                @foreach($categories as $category)
                                    <option {{$category->id === $product->category_id ? 'selected="true"' : ''}} value="{{$category->id}}">{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if(count($custom_field_with_values) > 0)
                        <div class="d-flex">
                            <div class="col-md-4"></div>
                            <div class="col-md-6">
                                <h5 class="h5 d-block mt-4">Custom fields
                                    <small>(those fields come from the category)</small>
                                </h5>
                            </div>
                        </div>
                        @foreach($custom_field_with_values as $field_tuple) @if($field_tuple)
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right"
                                       for="cf-{{$field_tuple[0]->id}}">{{$field_tuple[0]->name}}</label>

                                <div class="col-md-6">
                                    <input type="hidden"
                                           name="customFieldValue[{{$field_tuple[0]->id}}][valueId]"
                                           value="{{$field_tuple[1] ? $field_tuple[1]['id'] : ''}}"
                                    />

                                    <input id="cf-{{$field_tuple[0]->id}}"
                                           name="customFieldValue[{{$field_tuple[0]->id}}][value]"
                                           class="form-control {{ $errors->has("customFieldValue[{$field_tuple[0]->id}][value]") ? ' is-invalid' : '' }}"
                                           value="{{$field_tuple[1] ? $field_tuple[1]['value'] : ''}}"/>

                                    @if ($errors->has("customFieldValue[{$field_tuple[0]->id}][value]"))
                                        <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first("customFieldValue[{$field_tuple[0]->id}][value]") }}</strong>
                                            </span>
                                    @endif
                                </div>
                            </div>
                        @endif @endforeach
                    @endif

                    <div class="form-group row mb-0">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
