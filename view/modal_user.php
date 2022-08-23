<div class="modal" id="user_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Username and Password</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="input-group mb-3">
          <label class="input-group-text" for="old_password">Current Password</label>
          <input type="password" class="form-control" id="old_password">
        </div>
        <hr />
        <span><h6 style="display: inline;">Change Password</h6> <span class="notice" id="change_title"></span></span>
        <div id="new_pass_span">
          <div class="input-group mb-3">
            <label class="input-group-text" for="new_password1">New Password</label>
            <input type="password" class="form-control" id="new_password1">
          </div>
          <div class="input-group mb-3">
            <label class="input-group-text" for="new_password2">Repeat Password</label>
            <input type="password" class="form-control" id="new_password2">
          </div>
        </div>
        <div style="text-align: right">
          <button type="button" class="btn btn-success" onclick="updatePassword()">Update Password</button>
        </div>
        <hr />
        <span><h6 style="display: inline;">Change Username</h6> <span class="notice" id="user_title"></span></span>
        <div id="new_pass_span">
          <div class="input-group mb-3">
            <label class="input-group-text" for="new_username">New Username</label>
            <input type="text" class="form-control" id="new_username">
          </div>
          <div style="text-align: right">
            <button type="button" class="btn btn-success" onclick="updateUsername()">Update Username</button>
          </div>
          <br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
    </div>
  </div>
</div>