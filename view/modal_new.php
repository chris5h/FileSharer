<div class="modal" id="new_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Link</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid mt-3 w3-border w3-padding w3-round ws-grey">
            <div class="input-group mb-3">
              <span class="input-group-text">File Path</span>
              <input type="text" class="form-control" id="new_path" readonly>
            </div>
            <div class="input-group mb-3">
              <select id="new_notify" class="form-control">
                <option value="1">Email Notification</option>
                <option value="0">No Notification</option>
              </select>
            </div>
            <div class="input-group mb-3">
              <select id="new_exp" class="form-control"  onchange="checkInput('new','exp')">>
                <option value="0">No Expiration</option>
                <option value="1">Expires</option>
              </select>
              <input type="date" class="form-control" id="new_exp_date" disabled>
            </div>
            <div class="input-group mb-3">
              <select id="new_pw" class="form-control"  onchange="checkInput('new','pw')">>
                <option value="0">No Protection</option>
                <option value="1">Password Protect</option>
              </select>
              <input type="text" class="form-control" id="new_password" disabled>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="newLink()">Save</button>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>