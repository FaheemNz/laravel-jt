<div class="dropdown bootstrap-select">
    <select class="selectpicker" name="status" id="status"
        data-style="btn btn-primary btn-round btn-block" title="Select Status"
        value="">

    </select>
</div>
<script>
 $.get("{{ url('getOfferAllStatuses') }}", function (statuses) {
    let body = '';
    statuses.forEach(status => {
        body+=`<option value="${status.id}">${status.name}</option>`;
    });
    $("#status").html(body);
    $('.selectpicker#status').selectpicker('refresh');
 });
</script>
