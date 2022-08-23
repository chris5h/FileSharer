<?php
    $conf_path = 'conf.json';
    require_once 'conf.php';
    require_once '../model/User.php';
    if (!session_start()){
    die('Error initializing session!');
    }
    if (!$_SESSION['username'] || !$_SESSION['pw']){
    die('error - not logged in!');    
    } else  if (!User::Login($_SESSION['username'], $_SESSION['pw'])){
    die('error - not logged in!');
    }
if ($_POST['type']){
    require_once '../model/Link.php';
    switch ($_POST['type']){
        case 'new':
            $bob = new Link;
            $r = $bob->addFile($_POST['path'], $_POST['new_notify'], $_POST['new_pw'], $_POST['new_password'], $_POST['new_exp_date']);
            if ($r['success']){
                print json_encode($r, JSON_PRETTY_PRINT);
            }
        break;
        case 'shorten':
            $bob = new Link($_POST['guid']);
            $r = $bob->shortenLink($_POST['url']);
            print json_encode($r, JSON_PRETTY_PRINT);
            break;
        case 'edit':
            $bob = new Link($_POST['guid']);
            $bob->updateLink($_POST['edit_notify'], $_POST['edit_pw'], $_POST['edit_password'], ($_POST['edit_exp'] == 0 ? null : $_POST['edit_exp_date']));            
        break;
        case 'deactivate':
            $bob = new Link($_POST['guid']);
            $bob->deactivateLink();
        break;
        case 'delete':
            $bob = new Link($_POST['guid']);
            $bob->deleteLink();
        break;
        case 'activate':            
            $bob = new Link($_POST['guid']);
            $bob->activateLink();
        break;
        case 'settings':
            Settings::Save();
        break;
        case 'logout':
            session_start();
            session_destroy();
        break;
        case 'password':
            if ($_SESSION['pw'] == $_POST['new_password']){
                print json_encode(["success" => false, "message" => "new and old passwords match"], JSON_PRETTY_PRINT);
            } else if ($_SESSION['pw'] == $_POST['old_password']){
                User::editUser($_SESSION['username'], $_POST['new_password']);
                print json_encode(["success" => true], JSON_PRETTY_PRINT);
            }   else    {
                print json_encode(["success" => false, "message" => "invalid password"], JSON_PRETTY_PRINT);
            }
        break;
        case 'username':
            if ($_SESSION['username'] == $_POST['new_username']){
                print json_encode(["success" => false, "message" => "usernames match"], JSON_PRETTY_PRINT);
            } else if ($_SESSION['pw'] == $_POST['old_password']){
                User::editUser($_POST['new_username'], $_SESSION['pw']);
                print json_encode(["success" => true], JSON_PRETTY_PRINT);
            }   else    {
                print json_encode(["success" => false, "message" => "invalid password"], JSON_PRETTY_PRINT);
            }
        break;
        case 'test_email':
            $hank = Link::testEmail($_POST['smtp_server'], $_POST['smtp_port'], $_POST['smtp_security'], $_POST['smtp_username'], $_POST['smtp_password'], $_POST['smtp_security_type'], $_POST['email_notification'], $_POST['smtp_from_address']);
            print json_encode($hank, JSON_PRETTY_PRINT);
        break;
        case 'test_bitly':
            print Bitly::testKey($_POST['apikey']);
        break;
    }
    die();
}   elseif ($_GET['type']){
    require_once '../model/Link.php';
    switch ($_GET['type']){
        case 'links':
            $list = Link::getAllLinks();
            print json_encode($list, JSON_PRETTY_PRINT);
        break;
        case 'lookup':
            $bob = new Link($_GET['guid']);
            $bob->lookupLink();
            print json_encode($bob->getStats(), JSON_PRETTY_PRINT);
        break;
        case 'bitly':
            print json_encode(Bitly::getAll(), JSON_PRETTY_PRINT);
        break;
        case 'downloads':
            print json_encode(Link::getDownloads(), JSON_PRETTY_PRINT);
        break;
        case 'settings':
            print json_encode(Settings::Get(), JSON_PRETTY_PRINT);
        break;
    }
    die();
}