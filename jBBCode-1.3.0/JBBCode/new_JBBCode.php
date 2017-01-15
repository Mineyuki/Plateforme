<?php
	$parser->addBBCode("quote", '<blockquote>{param}</blockquote>');
	$parser->addBBCode("s", '<strike>{param}</strike>');
	$parser->addBBCode("sup", '<sup>{param}</sup>');
	$parser->addBBCode("sub", '<sub>{param}</sub>');
	$parser->addBBCode("list", '<ul>{param}</ul>');
	$parser->addBBCode("*", '<li>{param}</li>');
	$parser->addBBCode("offtop", '<span style="font-size:10px;color:#ccc">{param}</span>');
	$parser->addBBCode("td", '<td>{param}</td>');
	$parser->addBBCode("tr", '<tr>{param}</tr>');
	$parser->addBBCode("table", '<table class="wbb-table">{param}</table>');
	$parser->addBBCode("left", '<p style="text-align:left">{param}</p>');
	$parser->addBBCode("right", '<p style="text-align:right">{param}</p>');
	$parser->addBBCode("center", '<p style="text-align:center">{param}</p>');
?>
