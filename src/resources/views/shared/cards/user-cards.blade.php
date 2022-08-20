<div class="card card-user">
    <div class="card-body">
        @if($user)
            <div class="author">
                <a href="{{url('users').'/'.$user->id}}">
                    <img class="avatar border-gray" src="{{$user->avatar}}" style="width: 150px; height: 150px; object-fit: cover" alt="Avatar">
                    <h5 class="title">{{$user->first_name ." ". $user->last_name}}</h5>
                </a>
                <p class="description">
                    {{$user->email}}
                </p>
            </div>
            <p class="description text-center">
                {{$user->phone_no}}
            </p>
            <div class="text-center">
                <span><i class='now-ui-icons sport_trophy'></i> 5</span>
            </div>
        @endif
    </div>
    <hr>
</div>
