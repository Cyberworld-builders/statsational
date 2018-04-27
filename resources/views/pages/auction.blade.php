@extends('layouts.app')

@section('content')
<div class="container auction">
    <div class="row justify-content-center">
        <div class="col-md-12">

          <div class="row top-line">
            <div class="col-sm-12 col-md-6">
              <h2 class="pull-left">Auction</h2>
            </div>
            <div class="col-sm-12 col-md-6 text-right">
              <span>Status: online</span>
            </div>
          </div>

          <div class="row administration">
            <div class="col-sm-12 col-md-12">
              <div class="card">
                  <div class="card-header">Auction Administration</div>
                  <div class="card-body">
                      <div class="col-md-12">

                      </div>
                  </div>
              </div>
            </div>
          </div>

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
                          <div class="row">
                            <div class="col-md-3">
                              <button class="btn btn-secondary small form-control"><i class="fa fa-minus"></i></button>
                            </div>
                            <div class="col-md-3">
                              <input class="form-control" type="number">
                            </div>
                            <div class="col-md-3">
                              <button class="btn btn-secondary small form-control"><i class="fa fa-plus"></i></button>
                            </div>
                            <div class="col-md-3">
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

                      </div>
                  </div>
              </div>
            </div>
          </div>


        </div>
    </div>
</div>
@endsection
