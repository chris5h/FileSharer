<div class="modal" id="settings_modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Settings</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form autocomplete="off" id="settings_form">
        <div class="input-group mb-3">
          <label class="input-group-text" for="settings_protocol">Protocol</label>
          <select class="form-select" id="settings_protocol" name="settings_protocol">
            <option value="https">HTTPS</option>
            <option value="http">HTTP</option>
          </select>
        </div>
        <div class="input-group mb-3">
          <label class="input-group-text" for="settings_bitly">Use Bitly?</label>
          <select class="form-select" name="settings_bitly" id="settings_bitly" onchange="settingsCheck()">
              <option value="1">Yes</option>
              <option value="0">No</option>
          </select>
        </div>
        <div class="input-group mb-3 bitly_settings">
          <label class="input-group-text" for="settings_bitly_token">Bitly Token</label>
          <input type="text" class="form-control" id="settings_bitly_token" name="settings_bitly_token">
          <button class="btn btn-outline-primary" type="button" onclick="testBitly();" >Test Connection</button>
        </div>
        <div class="input-group mb-3">
          <label class="input-group-text" for="settings_email">Use Email Notifications</label>
          <select class="form-select" name="settings_email" id="settings_email" onchange="settingsCheck()">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
        <div class="input-group mb-3 email_settings">
          <label class="input-group-text" for="settings_email_recip">Recipient Address</label>
          <input type="text" class="form-control" id="settings_email_recip" name="settings_email_recip" >
          <button class="btn btn-outline-primary" type="button" onclick="testEmail();" >Send Test</button>
        </div>
        <div class="input-group mb-3 email_settings">
          <label class="input-group-text" for="settings_email_sender">Send From Address</label>
          <input type="text" class="form-control" id="settings_email_sender" name="settings_email_sender">
        </div>
        <div class="input-group mb-3 email_settings">
          <label class="input-group-text" for="settings_email_server">SMTP Server</label>
          <input type="text" class="form-control" id="settings_email_server" name="settings_email_server" >
        </div>

        <div class="input-group mb-3 email_settings">
          <label class="input-group-text" for="settings_email_port">SMTP Port</label>
          <input type="number" class="form-control" id="settings_email_port" step="1" name="settings_email_port" >
        </div>
        <div class="input-group mb-3 email_settings">
          <label class="input-group-text" for="settings_email_login">Use Login</label>
          <select class="form-select" id="settings_email_login" onchange="settingsCheck()" name="settings_email_login">
            <option value="1">Yes</option>
            <option value="0">No</option>
          </select>
        </div>
        <div class="input-group mb-3 email_settings email_login">
          <label class="input-group-text" for="settings_email_user">SMTP Username</label>
          <input type="text" class="form-control" id="settings_email_user" name="settings_email_user">
        </div>
        <div class="input-group mb-3 email_settings email_login">
          <label class="input-group-text" for="settings_email_pass">SMTP Password</label>
          <input type="text" class="form-control" id="settings_email_pass"  name="settings_email_pass">
        </div>
        <div class="input-group mb-3 email_settings email_login">
          <label class="input-group-text" for="settings_email_security">Security</label>
          <select class="form-select" id="settings_email_security" name="settings_email_security" >
            <option value="0">None</option>
            <option value="1">SSL</option>
            <option value="2">TLS</option>            
          </select>
        </div>
      </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success" onclick="editSettings()">Save</button>                  
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
   </div>
  </div>
</div>