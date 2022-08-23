<div class="modal" id="edit_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Edit Link</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid mt-3 w3-border w3-padding w3-round ws-grey">
            <div class="input-group mb-3">
              <span class="input-group-text">URL</span>
              <input type="text" class="form-control"  onclick="copyText($(this))" id="edit_url" readonly>
            </div>
            <div class="input-group mb-3">
              <span class="input-group-text">File</span>
              <input type="text" class="form-control" id="edit_path" readonly>
            </div>
            <div class="input-group mb-3">
              <select id="edit_notify" class="form-control">
                <option value="1">Email Notification</option>
                <option value="0">No Notification</option>
              </select>
            </div>
            <div class="input-group mb-3">
              <select id="edit_exp" class="form-control"  onchange="checkInput('edit','exp')">>
                <option value="0">No Expiration</option>
                <option value="1">Expires</option>
              </select>
              <input type="date" class="form-control" id="edit_exp_date" disabled>
            </div>
            <div class="input-group mb-3">
              <select id="edit_pw" class="form-control"  onchange="checkInput('edit','pw')">>
                <option value="0">No Protection</option>
                <option value="1">Password Protect</option>
              </select>
              <input type="text" class="form-control" id="edit_password" disabled>
            </div>
            <div class="mb-3 mt-3" id="short_edit" style="display: none;">
              <label><h5>Shortened Link</h5></label>
              <input type="text" class="form-control" id="short_link_edit" onclick="copyText($(this))" readonly>
            </div>            
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-success" onclick="editLink()">Save</button>
          <?= use_bitly ? '<button type="button" id="edit_shorten_button" class="btn btn-primary" onclick="shortenLink(\'edit\')">Shorten Link</button>' : '' ?>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>