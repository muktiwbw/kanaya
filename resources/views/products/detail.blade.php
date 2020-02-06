<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kanaya - {{$product->name}}</title>
</head>
<body>
    <h1>{{$product->name}}</h1>
    @foreach($product->images as $image)
    <img width="400" class="display-images" src="{{asset('img/'.$image->url)}}" alt="{{$product->name}}">
    @endforeach
    <p>{{$product->notes}}</p>
</body>
</html>