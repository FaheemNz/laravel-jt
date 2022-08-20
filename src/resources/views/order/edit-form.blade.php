<form id="orderForm" name="orderForm" class="form-horizontal" enctype='multipart/form-data'>
    <input type="hidden" name="order_id" id="order_id">
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="name" class="control-label">Name</label>
                  <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value=""
                    maxlength="50">
            </div>
            <div class="col">
                <label for="created_by" class="control-label">Customer Name</label>
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
                @include('shared.category-select')
            </div>
            <div class="col">
                <label for="url" class="control-label">Url</label>
                  <input type="text" class="form-control" id="url" name="url" placeholder="Enter Url" value=""
                   >
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
                {{-- <label for="weight" class="control-label">Weight</label>
                  <input type="number" class="form-control" id="weight" name="weight" placeholder="Enter Weight" value=""
                  min="1"> --}}
                  <div class="dropdown bootstrap-select">
                    <select class="selectpicker" name="weight" id="weight"
                        data-style="btn btn-primary btn-round btn-block" title="Select Weight"
                        value="">
                        <option value="1">Light</option>
                        <option value="2">Medium</option>
                        <option value="3">Heavy</option>
                    </select>
                </div>
            </div>

            <div class="col">
                <label for="quantity" class="control-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter Quantity" value=""
                min="1">
            </div>
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
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" name="with_box" id="with_box" type="checkbox" value="on">
                        With Box
                        <span class="form-check-sign">
                            <span class="check"></span>
                        </span>
                    </label>
                  </div>
            </div>
            <div class="col">
                <label for="needed_by" class="control-label">Needed By</label>
                  <input type="date" class="form-control" id="needed_by" name="needed_by" placeholder="Enter Needed By" value=""
                   >
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                @include('shared.destination-city-select')
            </div>
            <div class="col">
                @include('shared.from-city-select')
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                @include('shared.order-status-select')
            </div>
            <div class="col">
                @include('shared.currency-select')
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="customer_rating" class="control-label">Customer Rating</label>
                <input type="number" class="form-control" id="customer_rating" name="customer_rating" placeholder="Enter Customer Rating" value=""
                min="1">
            </div>
            <div class="col">
                <label for="customer_review" class="control-label">Customer Review</label>
                <textarea class="form-control" rows="5" id="customer_review" name="customer_review"></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <label for="traveler_rating" class="control-label">Traveler Rating</label>
                <input type="number" class="form-control" id="traveler_rating" name="traveler_rating" placeholder="Enter Traveler Rating" value=""
                min="1">
            </div>
            <div class="col">
                <label for="traveler_review" class="control-label">Traveler Review</label>
                <textarea class="form-control" rows="5" id="traveler_review" name="traveler_review"></textarea>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col">
                <div class="form-check">
                    <label class="form-check-label">
                        <input class="form-check-input" name="is_disputed" id="is_disputed" type="checkbox" value="on">
                        Is Disputed
                        <span class="form-check-sign">
                            <span class="check"></span>
                        </span>
                    </label>
                  </div>
            </div>
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
