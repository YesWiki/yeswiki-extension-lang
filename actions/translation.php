<?php
namespace Lang;

$loader = require __DIR__ . '/../vendor/autoload.php';

if (!defined("WIKINI_VERSION"))
{
    die("acc&egrave;s direct interdit");
}

$wikireq = $_REQUEST['wiki'];

// remove leading slash
$wikireq = preg_replace("/^\//", "", $wikireq);
// split into page/method, checking wiki name & method name (XSS proof)
if (preg_match('`^' . '(' . "[A-Za-z0-9]+" . ')/(' . "[A-Za-z0-9_-]" . '*)' . '$`', $wikireq, $matches)) {
    list(, $pageTag, $method) = $matches;
} elseif (preg_match('`^' . "[A-Za-z0-9]+" . '$`', $wikireq)) {
    $pageTag = $wikireq;
}

$page = $this->LoadPage($pageTag);

$lang = new Lang(
    $page["body"],
    $GLOBALS['prefered_language']
);

$listLang = $lang->getLangList();

$output = "";
foreach ($listLang as $lang) {
    $output .= "<a href=\"?wiki=$pageTag&lang=$lang\">";
    $text = $lang;
    $flagFile = 'tools/lang/presentation/images/'.$lang.'.png';
    if (file_exists($flagFile)) {
        $text = "<img src=\"$flagFile\" title=\"$lang\" alt=\"Flag$lang\"></img>";
    }
    $output .= "$text</a> ";
}

echo($output);
