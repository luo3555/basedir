<?php 
define('ROOT_DIR', $_SERVER["DOCUMENT_ROOT"]);
define('DS', DIRECTORY_SEPARATOR);

function dirList($path)
{
    $dirResorce = dir($path);
    $limit = ['.', '..', 'index.php'];

    $list = [];
    while (false !== ($entry = $dirResorce->read())) {
        if (!in_array($entry, $limit)) {
            $list[] = $entry;
        }
    }

    return $list;
}

function renderWebsiteLinks($dirNames, $currentDir = null)
{
    $html = '';

    if (empty($currentDir)) {
        $currentDir = ROOT_DIR;
    }

    foreach($dirNames as $dirName) {
        $dir = $currentDir . DS . $dirName;
        if (preg_match("/.*\.com$/", $dir) && is_dir($dir)) {
            $html .= '<li><a href="http://' . $dirName . '" target="_bank">' . $dirName . '[<a href="http://' . $dirName . '/admin" target="_bank">admin</a>]</li>';
        } else {
            if (is_dir($dirName)) {
                $html .= '<li><h4>' . $dirName . '</h4></li>';
                $html .= '<li>' . renderWebsiteLinks(dirList($dir), $dir) . '</li>';
            }
        }
    }

    return '<ul>' . $html . '</ul>';
}

echo renderWebsiteLinks(dirList(ROOT_DIR));

echo '<hr/>';

phpinfo()

?>