<?php
require_once "/path/to/jbbcode/Parser.php";

$parser = new JBBCode\Parser();

$parser->addBBCode("quote", '<blockquote>{param}</blockquote>');
$parser->addBBCode("code", '<pre class="code">{param}</pre>', false, false, 1);
