@extends('layouts.products')
@section('content')
   <div class="album py-5 bg-light">
    <div class="container">
      <div class="row">
        @if ($product->count())
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
        @endif

      </div>
    </div>
  </div>

@endsection
