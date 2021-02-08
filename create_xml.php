<?php

$dom = new DOMDocument();
    $dom->encoding = 'utf-8';
    $dom->xmlVersion = '1.0';
    $dom->formatOutput = true;

$xml_file_name = 'database.xml';
    $root = $dom->createElement('Users');
    $dom->appendChild($root);

$dom->save($xml_file_name);