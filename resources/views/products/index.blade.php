@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if(auth()->check() && (auth()->user()->hasRole('Super admin') || auth()->user()->hasRole('Admin manager')))
                    <div>
                        <a href="{{route('newProductForm')}}">
                            <button class="btn btn-primary">Add New</button>
                        </a>
                    </div>
                @endif

                @if(count($products) > 0)
                    <ul class="list-group mt-3">
                        @foreach($products as $product)
                            <li class="card mb-3" style="width: 20rem;">
                                <img src="{{$product->image ? $product->image->url : ''}}" class="card-img-top" alt="">
                                <div class="card-body">
                                    <h5 class="card-title">{{$product->name}}
                                        <small>(from {{$product->category->name}})</small>
                                    </h5>
                                    @if(auth()->check() && (auth()->user()->hasRole('Super admin') || auth()->user()->hasRole('Admin manager')))
                                        <a href="{{route('productUpdateForm', $product)}}" class="card-link">Edit</a>
                                        <a href="{{route('productDeleteConfirm', $product)}}"
                                           class="card-link">Delete</a>
                                    @endif
                                </div>
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
