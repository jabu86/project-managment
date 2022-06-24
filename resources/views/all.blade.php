@extends('layouts.products')
@section('content')
   @include('layouts.hero')
   <div class="album py-5 bg-light">
    <div class="container">
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        @if ($products->count())
            @foreach ( $products as $product )
                <div class="col">
                    <div class="card d-fill" style="height:calc(100%)">
                        <svg class="bd-placeholder-img card-img-top" width="100%" height="225" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Thumbnail" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#55595c"></rect><text x="50%" y="50%" fill="#eceeef" dy=".3em">Thumbnail</text></svg>

                        <div class="card-body">
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text">{{ $product->user->name }}</p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                <a type="button" href="{{ route('product', $product->id) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                <button type="button" class="btn btn-sm btn-outline-secondary">Edit</button>
                                </div>
                                <small class="text-muted">{{ $product->created_at }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p>There are no products</p>
        @endif
      </div>
    </div>
  </div>

@endsection
