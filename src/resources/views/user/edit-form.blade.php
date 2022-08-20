<form id="userForm" name="userForm" action="{{$user? route("users.update", $user) : route("users.store")}}" method="post" class="form-horizontal" enctype='multipart/form-data'>
    @csrf
    @if($user)
        @method("PUT")
    @endif
    <input type="file" onchange="previewImage(event)" class="d-none" name="image" id="image">
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="first_name" class="control-label">First Name</label>
                  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter First Name" value="{{$user? $user->first_name : old("first_name")}}"
                    maxlength="50">
            </div>
            <div class="col">
                <label for="last_name" class="control-label">Last Name</label>
                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter Last Name" value="{{$user? $user->last_name:old("last_name")}}"
                  maxlength="50">
            </div>
            <div class="col">
                <label for="phone_no" class="control-label">Phone No</label>
                <input type="text" class="form-control" id="phone_no" name="phone_no" placeholder="Enter Phone No" value="{{$user? $user->phone_no:old("phone_no")}}"
                >
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="email" class="control-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" value="{{$user? $user->email:old("email")}}"
                {{$user? "disabled" : ""}}>
            </div>
            <div class="col">
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            <label for="new_password" class="control-label">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="Enter New Password">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <div class="form-group">
                    <div class="row">
                        <div class="col">
                            @include('shared.currency-select')
                        </div>
                    </div>
                </div>
            </div>
            @if(auth()->user()->isAdmin)
                <div class="col">
                    <label for="rating" class="control-label">Rating</label>
                    <input type="number" class="form-control" id="rating" name="rating" placeholder="Enter Rating" value="{{old("rating")}}" disabled>
                </div>
                <div class="col d-flex align-items-center">
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input" {{$user && $user->is_disabled? "checked" : ""}} name="is_disabled" id="is_disabled" type="checkbox" value="on">
                                Is Disabled
                            <span class="form-check-sign">
                                <span class="check"></span>
                            </span>
                        </label>
                    </div>
                </div>
                <div class="col-12">
                    <label for="admin_review" class="control-label">Admin Review</label>
                    <textarea class="form-control" rows="5" id="admin_review" name="admin_review">{{old("admin_review")}}</textarea>
                </div>
            @endif
        </div>
    </div>
    <div class="col-sm-offset-2 col-sm-12">
      <button type="submit" class="btn btn-primary btn-round float-right" id="saveBtn" value="create">
          Save changes
      </button>
    </div>
  </form>

<script>
    function previewImage(event) {
        const [file] = event.target.files
        if (file) {
            document.getElementById("imgPrev").src = URL.createObjectURL(file)
        }
    }
</script>
