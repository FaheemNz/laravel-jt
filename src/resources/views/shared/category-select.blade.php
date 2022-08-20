<div class="form-group">
    <label for="category" class="control-label">Category</label>
    <select class="selectpicker form-control show-tick"
            name="category_id"
            id="category"
            data-live-search="true"
            data-size="5"
            data-style="btn btn-outline-primary btn-round btn-block"
            title="Select Category">
    </select>
</div>
<script>
    (function() {
        $.get("{{url('getAllCategories') }}", function (categories) {
            console.log("Categories Loaded");
            let body = '';
            categories.forEach(category => {
                body+=`<option value="${category.id}">${category.name}</option>`;
            });
            $("#category").html(body);
            $('.selectpicker#category').selectpicker('refresh');
        });
    })();
</script>
