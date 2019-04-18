<h3>Products created in the date range {{$date['from']->format('d M Y')}} - {{$date['to']->format('d M Y')}}</h3>

<ul>
    @foreach($products as $product)
        <li>{{$product->name}}</li>
    @endforeach
</ul>