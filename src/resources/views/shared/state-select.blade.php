<div class="form-group">
    <label for="category" class="control-label">State</label>
    <input id="search_state" type="text" placeholder="Search State" class="form-control mb-3">
    <select class="form-control" name="state_id" id="state"></select>
</div>
<script>
    $('#search_state').on('keyup', function () {
        let name = $(this).val();
        fetchStates(name);
    })
    function fetchStates(name) {
        let url = "{{ url('getAllStates') }}";
        if(name){
            url+= "?q="+name;
        }
        $.get(url, function (states) {
            let body = '';
            states.forEach(state => {
                body+=`<option value="${state.id}">${state.name}, ${state.country.name}</option>`;
            });
            $("#state").html(body);
        });
    }
    fetchStates();
</script>
