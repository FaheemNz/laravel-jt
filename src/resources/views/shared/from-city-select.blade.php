<div class="form-group">
    <label for="from_city" class="control-label">From City</label>
    <input id="search_from_city" type="text" placeholder="Search City" class="form-control mb-3">
    <select class="form-control" name="from_city_id" id="from_city"></select>
</div>
<script>
    $('#search_from_city').on('keyup', function () {
        let name = $(this).val();
        fetchCities(name);
    })
     function fetchCities(name) {
        let url = "{{ url('getAllCities') }}";
        if(name){
            url+= "?q="+name;
        }
         $.get(url, function (cities) {
             let body = '';
             cities.forEach(city => {
                 body+=`<option value="${city.id}">${city.name}, ${city.state.country.name}</option>`;
             });
             $("#from_city").html(body);
         });
     }
     fetchCities();
</script>
