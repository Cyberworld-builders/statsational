@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">Auctions</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <ul>
                      @foreach($auctions as $auction)
                        <li>
                          <a class="dropdown-item" href="/auction/{{ $auction->id }}" >
                              {{ __( $auction->name ) }}
                          </a>
                        </li>
                      @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
