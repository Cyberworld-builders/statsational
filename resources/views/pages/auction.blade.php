@extends('layouts.dash')
@section('content')
  <Auction>
    @if(count($auction->items)>0)
      <auction-bidding :auction="{{ $auction }}" :bids="{{ $bids }}"></auction-bidding>
    @endif
    <div class="row justify-content-center">
        <div class="col-md-12 auction-room">
          <div class="row money-spent">
          	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-12">
          		<span>Money Spent ($)</span> &nbsp<span class="spent">{{ 200 }}</span>
          	</div>
          </div>
          {{-- <div class="row top-line">
            <div class="col-md-12">
              <div class="row">
                <div class="col-sm-12 col-md-6">
                  <h2 class="pull-left">{{ $auction->name }}</h2>
                </div>
                <div class="col-sm-12 col-md-6 text-right">
                  <span class="text-right">Status: online</span>
                </div>
              </div>
            </div>
          </div> --}}
          {{-- @if ($auction->is_owner())
            <div class="row administration">
              <tabs></tabs>
              <div class="col-sm-12 col-md-12">
                <div class="card">
                    <div class="card-header">Auction Administration</div>
                    <div class="card-body">
                        <div class="col-md-12 auction-controls">
                          <Tabs>
                            <auction-control>Start Next Item</auction-control>
                            <auction-control>Restart Clock</auction-control>
                            <auction-control>Undo Last Bid</auction-control>
                            <auction-control>End Auction</auction-control>
                            <auction-control>Reload App</auction-control>
                          </Tabs>
                        </div>
                    </div>
                </div>
              </div>
            </div>
          @endif --}}

          <div class="row ">

          	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-10">


              <div class="row">
              	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-8">
                  <auction-items
                    user="{{ $user->id }}"
                    :auction="{{ $auction }}"
                    >
                  </auction-items>
              	</div>
              	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4">

              	</div>
              </div>

          	</div>

          	<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-2">
              <bidders-overview :auction="{{ $auction }}" :bids="{{ $bids }}"></bidders-overview>
          	</div>

          </div>



          <div class="row messaging">
            <div class="col-sm-12 col-md-12">
              <div class="card">
                  <div class="card-header">Message Board</div>
                  <div class="card-body">
                      <div class="col-md-12">
                            <chat :auction="{{ $auction }}" :user="{{ Auth::user() }}"></chat>
                      </div>
                  </div>
              </div>
            </div>
          </div>

        </div>
    </div>
  </Auction>
@endsection
