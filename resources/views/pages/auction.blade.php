@extends('layouts.app')
@section('content')
  <Auction>
    <div class="row justify-content-center">
        <div class="col-md-12">
          <div class="row top-line">
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
          </div>

          @if ($auction->is_owner())
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
          @endif

          <auction-bidding auction_id="{{ $auction->id }}" current_bid="0"></auction-bidding>

          <auction-items
            auction_id="{{ $auction->id }}"
            admin="{{ $auction->is_admin }}">
          </auction-items>

          <div class="row messaging">
            <div class="col-sm-12 col-md-12">
              <div class="card">
                  <div class="card-header">Message Board</div>
                  <div class="card-body">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-sm-12 col-md-7">
                            <textarea class="form-control" readonly></textarea>
                            <br />
                            <div class="row">
                              <div class="col-sm-12 col-md-10 mt-1">
                                <input class="form-control" placeholder="Type a message..."/>
                              </div>
                              <div class="col-sm-3 col-md-2 pull-right text-right mt-1">
                                <button class="btn btn-primary ">Send</button>
                              </div>
                            </div>
                          </div>
                          <div class="col-sm-12 col-md-5">
                            <h4>Online Users:</h4>
                            <ul>
                              <li>{{ $auction->user->name }} (owner)</li>
                              @foreach ($auction->users as $user)
                                <li>{{ $user->name }}</li>
                              @endforeach
                            </ul>
                          </div>
                        </div>
                      </div>
                  </div>
              </div>
            </div>
          </div>


        </div>
    </div>
  </Auction>
@endsection
