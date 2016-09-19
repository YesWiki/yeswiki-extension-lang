<?php
namespace Lang;

$loader = require __DIR__ . '/../vendor/autoload.php';

if (!defined("WIKINI_VERSION"))
{
    die ("acc&egrave;s direct interdit");
}

$includedpage = $this->LoadPage(trim($this->GetParameter('page')));

$lang = new Lang(
    $includedpage["body"],
    $GLOBALS['prefered_language']
);

if (!isset($_GET['lang'])) {
    $_GET['lang'] = $GLOBALS['prefered_language'];
}

$includedpage["body"] = $lang->get($_GET['lang']);

// Hack : mise a jour du cache avec la nouvelle version.
$this->CachePage($includedpage);
