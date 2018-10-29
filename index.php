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
        if (preg_match("/.*\.[a-z]{2,3}$/", $dir) && is_dir($dir)) {
            $html .= '<li><a href="http://' . $dirName . '" target="_blank">' . $dirName . '[<a href="http://' . $dirName . '/admin" target="_blank">admin</a>]</li>';
        } else {
            if (is_dir($dirName)) {
                $html .= '<li><h4><a href="http://localhost/' . $dirName . '"</a>' . $dirName . '</h4></li>';
                $subDir = renderWebsiteLinks(dirList($dir), $dir);
                $html =  !empty($subDir) ? $html . '<li>' . $subDir . '</li>' :  $html;
            }
        }
    }

    $html = !empty($html) ? '<ul>' . $html . '</ul>' : $html;

    return $html;
}

echo renderWebsiteLinks(dirList(ROOT_DIR));

echo '<hr/>';

phpinfo()

?>