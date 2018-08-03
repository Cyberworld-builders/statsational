@extends('layouts.dash')
@section('content')
  {{-- <Auction :auction="{{ $auction }}" :bids="{{ $bids }}" :user="{{ $user }}"></Auction> --}}

  <input id="auction_id" type="hidden" value="{{ $auction->id }}">
  <input id="user_id" type="hidden" value="{{ $user->id }}">



  <div class="auction">   <!-- the entire auction room -->


    <nav class="top-bar"> <!-- top bar nav -->


      <img class="auction-pools-logo" src="/images/logo.png" />



    </nav> <!-- end top bar nav -->



    <nav class="control-bar resizable"> <!-- bidding controls bar     -->





    </nav> <!-- end bidding controls bar -->




    <div class="widgets"> <!-- the main content area     -->




    </div> <!-- end main content area     -->


  </div> <!-- end auction room -->

@endsection
