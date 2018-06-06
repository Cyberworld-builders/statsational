@extends('layouts.dash')
@section('content')
  <Auction :auction="{{ $auction }}" :bids="{{ $bids }}" :user="{{ $user }}"></Auction>
@endsection
