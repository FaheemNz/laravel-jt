 <div class="row">
   @if($offer)
      <div class="col-md-12">
          <div class="card">
              <div class="card-header">
                  <h5 class="title">Offer #{{$offer->id}} {{$offer->trashed() ? '<span class="badge badge-danger">Deleted</span>' : '' }}</h5>
              </div>
              <div class="card-body">
                  <div class="row">
                      <div class="col-12">
                          <h6>Select {{$offer->status == \App\Utills\Constants\OfferStatus::ACCEPTED? "Offer " : "Counter Offer "}} Summary</h6>
                          <span class="d-flex align-items-center justify-content-between bg-light p-2">
                                <span>Traveller:</span> <b><a href="{{route('users.show', $offer->user)}}" target="_blank">{{$offer->user->first_name}} {{$offer->user->last_name}}</a></b>
                          </span>
                          <span class="d-flex align-items-center justify-content-between p-2">
                                <span>Price:</span> <b>{{$offer->order->currency->symbol}} {{number_format($offer->price)}}</b>
                          </span>
                          <span class="d-flex align-items-center justify-content-between bg-light p-2">
                                <span>Reward:</span> <b>{{$offer->order->currency->symbol}} {{$offer->status != \App\Utills\Constants\OfferStatus::ACCEPTED? number_format($offer->counterOffer->reward) : number_format($offer->reward)}}</b>
                          </span>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    @endif
</div>

