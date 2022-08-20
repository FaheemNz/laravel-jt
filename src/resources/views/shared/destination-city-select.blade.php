<div class="form-group">
    <label for="destination_city" class="control-label">Destination City</label>
    <input id="search_destination_city" type="text" placeholder="Search City" class="form-control mb-3">
    <select class="form-control" name="destination_city_id" id="destination_city"></select>
</div>
<script>
    $('#search_destination_city').on('keyup', function () {
        let name = $(this).val();
        fetchDestinationCities(name);
    })
    function fetchDestinationCities(name) {
        let url = "{{ url('getAllCities') }}";
        if(name){
            url+= "?q="+name;
        }
        $.get(url, function (cities) {
            let body = '';
            cities.forEach(city => {
                body+=`<option value="${city.id}">${city.name}, ${city.state.country.name}</option>`;
            });
            $("#destination_city").html(body);
        });
    }
    fetchDestinationCities();
</script>
