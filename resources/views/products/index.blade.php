@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(auth()->check())
                    <div>
                        <a href="{{route('newProductForm')}}">
                            <button class="btn btn-primary">Add New</button>
                        </a>
                    </div>
                @endif

                @if(count($products) > 0)
                    <ul class="list-group mt-3">
                        @foreach($products as $product)
                            <li class="list-group-item row" style="display: flex;">
                                <span class="col-1">
                                    <img style="width:50px;height:50px;object-fit:contain;" class="col-2"
                                         src="{{$product->image->url}}" alt=""/>
                                </span>
                                <div class="col-8">
                                    <h5 class="h5">{{$product->name}}
                                        <small>(from {{$product->category->name}})</small>
                                    </h5>
                                </div>
                                <span>
                                    <a class="mr-2" href="{{route('productUpdateForm', $product)}}">Edit</a>
                                    <a class="mr-2" href="{{route('productDeleteConfirm', $product)}}">Delete</a>
                                </span>
                            </li>
                        @endforeach
                    </ul>
                    <div class="mt-3 d-flex justify-content-center">{{ $products->links() }}</div>
                @elseIf(count($products) === 0)
                    <h3 class="text-center m-3">No products added yet</h3>
                @endif
            </div>
        </div>
    </div>
@endsection
