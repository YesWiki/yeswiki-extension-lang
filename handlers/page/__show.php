<?php
namespace Lang;

if (!defined("WIKINI_VERSION"))
{
    die ("Accés direct interdit");
}

$loader = require __DIR__ . '/../../vendor/autoload.php';

$lang = new Lang(
    $this->page["body"],
    $GLOBALS['prefered_language']
);

if (!isset($_GET['lang'])) {
    $_GET['lang'] = $GLOBALS['prefered_language'];
}

$this->page["body"] = $lang->get($_GET['lang']);
