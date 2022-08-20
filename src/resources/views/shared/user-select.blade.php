<div class="form-group">
    <label for="{{$field ?? 'user_id'}}" class="control-label">{{$label ?? "User"}}</label>
    <select class="selectpicker form-control show-tick"
            name="{{$field ?? 'user_id'}}"
            id="{{$field ?? 'user_id'}}"
            data-live-search="true"
            data-size="5"
            data-style="btn btn-outline-primary btn-round btn-block"
            title="Select {{$label ?? 'User'}}">
    </select>
</div>
<script>

    $.get("{{ url('getAllUsers') }}", function (users) {
        let field = "{{$field ?? 'user_id'}}";
        let body = '';
        users.forEach(user => {
            body+=`<option value="${user.id}">${user.first_name} ${user.last_name}</option>`;
        });
        $(`#${field}`).html(body);
        $(`.selectpicker#${field}`).selectpicker('refresh');
    });
</script>

