<div class="form-group">
    <label for="category" class="control-label">Country</label>
    <input id="search_country" type="text" placeholder="Search Country" class="form-control mb-3">
    <select class="form-control" name="country_id" id="country"></select>
</div>
<script>
    $('#search_country').on('keyup', function () {
        let name = $(this).val();
        fetchCountries(name);
    })
    function fetchCountries(name) {
        let url = "{{ url('getAllCountries') }}";
        if(name){
            url+= "?q="+name;
        }
        $.get(url, function (countries) {
            let body = '';
            countries.forEach(country => {
                body+=`<option value="${country.id}">${country.name}</option>`;
            });
            $("#country").html(body);
        });
    }
    fetchCountries();
</script>
