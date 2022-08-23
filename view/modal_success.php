<div class="modal" id="success_modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">New Link</h4>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3 mt-3">
            <label><h5>Link</h5> Click to Copy</label>
            <input type="text" class="form-control" id="confirm_path" readonly onclick="copyText($(this))">
          </div>
          <div class="mb-3 mt-3">
            <label><h5>Password</h5> Click to Copy</label>
            <input type="text" class="form-control" id="confirm_pw" onclick="copyText($(this))" readonly>
          </div>
          <div class="mb-3 mt-3">
            <label><h5>Expiration</h5></label>
            <input type="date" class="form-control" id="confirm_exp" readonly>
          </div>
          <div class="mb-3 mt-3" id="short" style="display: none;">
            <label><h5>Shortened Link</h5></label>
            <input type="text" class="form-control" id="short_link" onclick="copyText($(this))" readonly>
          </div>
        </div>
        <div class="modal-footer">
          <?= use_bitly ? '<button type="button" id="new_short" class="btn btn-primary" onclick="shortenLink(\'new\')">Shorten Link</button>' : '' ?>
          <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
  </div>
</div>