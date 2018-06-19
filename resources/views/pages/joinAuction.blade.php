@extends('layouts.app')
@section('content')

<div class="auction">   <!-- the entire auction room -->

    <div class="row justify-content-center">
        <div class="col-md-12">


          <div class="container-fluid">
          	<div class="row">
          		<div class="col-md-3">
          		</div>
          		<div class="col-md-6">

                <join-form auction_id="{{ $auction->id }}" auction_name="{{ $auction->name }}"></join-form>


          		</div>
          		<div class="col-md-3">
          		</div>
          	</div>
          </div>


        </div>
    </div>
</div>

</div>
@endsection
