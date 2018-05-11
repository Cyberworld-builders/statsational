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

          <div class="row bidding">
            <div class="col-sm-12 col-md-6 ">
              <div class="row info-block">
                  <div class="hidden-xs hidden-sm col-md-3"></div>
                  <div class="col-md-8 col-lg-8">
                    <h3>Russell Henley (#50)</h3>
                    <ul>
                      <li>Bid: $0</li>
                      <li>Time Remaining: 00:01</li>
                      <li>High Bid: $0</li>
                    </ul>
                  </div>
                  <div class="hidden-xs hidden-sm col-md-1 "></div>
                  <div class="col-md-12 text-center">
                    <hr />
                    <p>Current Purchases: ($0) - Total: ($0)</p>
                    <button class="btn btn-primary form-control input-block-level">$1 (min bid)</button>
                    <br /><br />
                    <div class="row">
                      <div class="hidden-xs hidden-sm col-md-3"></div>
                      <div class="col-sm-12 col-md-6">
                          <div class="row text-center">
                            <div class="col-md-3 bid-button">
                              <button class="btn btn-secondary small form-control"><i class="fa fa-minus"></i></button>
                            </div>
                            <div class="col-md-3 bid-button">
                              <input class="form-control" type="number" >
                            </div>
                            <div class="col-md-3 bid-button">
                              <button class="btn btn-secondary small form-control"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-md-3 bid-button">
                              <button class="btn btn-primary small form-control"><strong>Bid</strong></button>
                            </div>
                          </div>
                        </div>
                      <div class="hidden-xs hidden-sm col-md-3"></div>
                    </div>
                  </div>
              </div>
            </div>
            <div class="col-sm-12 col-md-6 ">
              <div class="card">
                  <div class="card-header">Bids</div>
                  <div class="card-body">
                      <div class="col-md-12">
                      </div>
                  </div>
              </div>
            </div>
          </div>
          <div class="row messaging">
            <div class="col-sm-12 col-md-12">
              <div class="card">
                  <div class="card-header">Message Board</div>
                  <div class="card-body">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-sm-12 col-md-7">
                            <div class="panel-body">
                                <chat-messages :messages="messages"></chat-messages>
                            </div>
                            <br />
                            <div class="row">
                              <chat-form
                                  v-on:messagesent="addMessage"
                                  :user="{{ Auth::user() }}"
                              ></chat-form>
                            </div>
                          </div>
                          <div class="col-sm-12 col-md-5">
                            <h4>Online Users:</h4>

                            <ul>
                                {{ $auction->user->name }} (owner)
                                @foreach ( $auction->users as $user)
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
