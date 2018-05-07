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

                    <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">Id</th>
                          <th scope="col">Auction</th>
                          <th scope="col">Start Time</th>
                          <th scope="col">Owner</th>
                        </tr>
                      </thead>
                      <tbody>

                        @foreach($auctions as $auction)
                            <tr>
                              <th scope="row">{{ $auction->id }}</th>
                              <td><a class="dropdown-item" href="/auction/{{ $auction->id }}" >{{ __( $auction->name ) }}</a></td>
                              <td>{{ $auction->start_time }}</td>
                              <td>{{ $auction->user->name }}</td>
                            </tr>
                        @endforeach
                      </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
