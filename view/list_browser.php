<?
define("basedir", "D:/");
if ($_GET['path']){
    $path = (implode('/',$_GET['path'])).'/';
}
$path = basedir.$path;
$list = scandir($path);
$files = [];
$folders = [];
foreach ($list as $item) {
    if (!in_array($item, ['.','..']))   {
        if(is_file($path.$item)){
            
            $files[] = str_replace('/','\\',$path.$item);
        }   else    {
            $folders[] = $item;
        }
    }
}

?>
<br>
<h4>Folders:</h4>
<ul class="folder_list">
    <?php
    if ($_GET['path']){
        print "<li class='back'><a href='javascript:history.back()'>Go Back</a></li>\r\n";
    }    
    foreach ($folders as $folder){
        print "<li class='folder'><a href='".makeLink($folder)."'>$folder</a></li>\r\n";
    }
    ?>
</ul><br>
<h4>Files</h4>
<ul class="folder_list">
<?
foreach ($files as $file){
    print "<li class='file' onclick='makeLink($(this))'>$file</li>\r\n";
}
print "<ul>";

function makeLink($folder){
    $url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]".strtok($_SERVER["REQUEST_URI"],'?')."?";
    foreach (array_merge((is_null($_GET['path']) ? [] : $_GET['path']),[$folder]) as $key => $line){
        if ($key > 0){$url .= "&";}
        $url .= "path[]=".urlencode($line);
    }
    return $url;
}   
?>