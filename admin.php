<?
  $conf_path = 'inc/conf.json';
  require_once 'inc/conf.php';
  require_once 'model/User.php';
  require_once 'model/Settings.php';
  Settings::Load();
  if (!session_start()){
    die('Error initializing session!');
  }
  if ($_POST){
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['pw'] = $_POST['password'];
    header("Location: admin.php");
  die();
  }
  if (!$_SESSION['username'] || !$_SESSION['pw']){
    require_once 'view/login.php';
  } 
  if (!User::Login($_SESSION['username'], $_SESSION['pw'])){
    $error = true;
    require_once 'view/login.php';
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Link Manager</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <style>
    li.folder {
      list-style-type: '📁';
      padding-inline-start: 1ch;
      cursor: pointer;
    }
    li.folder:hover {
      font-size: 105%;
      font-weight: bold;
      background-color: lightyellow;
    }
    li.file {
      list-style-type: '📋';
      padding-inline-start: 1ch;
      cursor: pointer;
    }
    li.file:hover {
      font-size: 105%;
      font-weight: bold;
      background-color: lightyellow;
    }
    li.back {
      list-style-type: '🔙';
      padding-inline-start: 1ch;
      cursor: pointer;
      font-size: 110%;
      margin-bottom: 10px;
    }
    li.folder:back {
      font-weight: bold;
      background-color: lightyellow;
    }
    .inactive_links{
      display: none;
      font-style: italic;
    }
    .inactive_links input[type=text] {
      font-style: italic;
    }
    .inactive_links .del_button{
      font-style: normal;
    }
    #links_header_title:hover {
      font-weight: bold;
    }
    .link_bar {
      white-space: nowrap;
      width: 1px;
    }
    .oldlink:hover .del_button {
      display: block;
    }
    .oldlink .del_button {
      display: none;
    }
    .del_button {
      cursor: pointer;
    }
    .edit_buttons {
      margin: auto;
      width: 125px;
      vertical-align:middle;
      text-align: center;
      cursor: pointer;
      font-size: 125%;
      font-weight: bold;
    }
    .folder_list li:nth-child(even) {
      background: #f2f2f2
    }
    .link_table table { 
      border-collapse: collapse; 
    }
    .link_table tr { 
      border: none; 
    }
    .link_table td {
      border-right: solid 1px black; 
      border-left: solid 1px black;
      border-top: none;
      border-bottom: none;
    }
    .link_table tbody {
      border:1px solid black
    }
    .notice {
      font-size: 100%;
      color: red;
    }
</style>
  </head>
<body>
  <div class="container" id="menu" style="padding-top: 25px;">
    <ul class="pagination pagination-lg">
      <li id="menu_browser" class="page-item active" onclick="menuClick($(this))"><a class="page-link" href="javascript:void(0)">New Link</a></li>
      <li id="menu_link" class="page-item" onclick="menuClick($(this))"><a class="page-link" href="javascript:void(0)">Existing Links</a></li>
      <li id="menu_downloads" class="page-item" onclick="menuClick($(this))"><a class="page-link" href="javascript:void(0)">Downloads</a></li>
      <li id="menu_settings" class="page-item" onclick="loadSettings();$('#settings_modal').modal('show');syncLabels('settings_modal');" data-bs-toggle="tooltip" title="Settings"><a class="page-link" href="javascript:void(0)">⚙️</a></li>
      <li id="menu_settings" class="page-item" onclick="$('#user_modal').modal('show');syncLabels('user_modal');"  data-bs-toggle="tooltip" title="Username/Password"><a class="page-link" href="javascript:void(0)">🔑</a></li>
      <li id="menu_settings" class="page-item" onclick="logout();"  data-bs-toggle="tooltip" title="Log Off"><a class="page-link" href="javascript:void(0)"><img src="inc/logout.png" style="height: 1.1em;"></a></li>
    </ul>
  </div>
  
  <div class="container window" id="browser">
      <? require_once 'view/list_browser.php'; ?>
  </div>
  <div class="container window" id="links" style="display:none;">
      <? require_once 'view/list_links.php'; ?>
  </div>
  <div class="container window" id="downloads" style="display:none;">
      <? require_once 'view/list_downloads.php'; ?>
  </div>
  <? require_once 'view/modal_edit.php' ?>
  <? require_once 'view/modal_new.php' ?>
  <? require_once 'view/modal_success.php' ?>
  <? require_once 'view/modal_user.php' ?>
  <? require_once 'view/modal_settings.php' ?>
  <script src="/inc/calls.js"></script>
  <script>
    var active_guid;
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl);
    })
  </script>
</body>
</html>