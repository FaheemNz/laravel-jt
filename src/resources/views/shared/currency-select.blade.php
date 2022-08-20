<div class="form-group">
    <label for="currency" class="control-label">Currency</label>
    <select class="selectpicker form-control show-tick"
            name="currency_id"
            id="currency"
            data-live-search="true"
            data-size="5"
            data-style="btn btn-outline-primary btn-round btn-block"
            title="Select Currency">
    </select>
</div>
<script>
    @php
        $user = $user ?? auth()->user();
    @endphp
    let authCurrencyId = @json($user->currency_id);
    fetch("{{url('getAllCurrencies') }}")
        .then(response => response.json())
        .then(currencies => {
            let body = '';
            currencies.forEach(currency => {
                body+=`<option data-subtext="${currency.symbol}" value="${currency.id}" selected="selected">${currency.short_code}</option>`;
            });
            $("#currency").html(body);
            $('.selectpicker#currency').selectpicker('refresh');
            $('.selectpicker#currency').selectpicker('val', authCurrencyId);
        });
</script>
