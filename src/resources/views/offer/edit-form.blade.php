<form id="offerForm" name="offerForm" class="form-horizontal" enctype='multipart/form-data'>
    <input type="hidden" name="offer_id" id="offer_id">
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="created_by" class="control-label">Traveler Name</label>
                <div>
                  <input type="text" class="form-control" id="created_by" name="created_by" placeholder="Enter Customer Name" value=""
                    maxlength="50" disabled>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="description" class="control-label">Description</label>
                <textarea class="form-control" rows="5" id="description" name="description"></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="price" class="control-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" placeholder="Enter Price" value=""
                min="1">
            </div>
            <div class="col">
                <label for="reward" class="control-label">Reward</label>
                  <input type="number" class="form-control" id="reward" name="reward" placeholder="Enter Reward" value=""
                   min="1">
            </div>
            <div class="col">
                <label for="service_charges" class="control-label">Service Charges</label>
                <input type="number" class="form-control" id="service_charges" name="service_charges" placeholder="Enter Quantity" value=""
                min="1">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="expiry_date" class="control-label">Expiry Date</label>
                  <input type="date" class="form-control" id="expiry_date" name="expiry_date" placeholder="Enter Expiry Date" value=""
                   >
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                @include('shared.offer-status-select')
            </div>
            <div class="col">
                @include('shared.currency-select')
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" name="is_disabled" id="is_disabled" type="checkbox" value="on">
                        Is Disabled
                        <span class="form-check-sign">
                            <span class="check"></span>
                        </span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="admin_review" class="control-label">Admin Review</label>
                <textarea class="form-control" rows="5" id="admin_review" name="admin_review"></textarea>
            </div>
        </div>
    </div>
    <div class="col-sm-offset-2 col-sm-12">
      <button type="submit" class="btn btn-primary btn-round float-right" id="saveBtn" value="create">Save
        changes
      </button>
    </div>
  </form>
