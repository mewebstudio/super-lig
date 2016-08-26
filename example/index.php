<?php

require '../vendor/autoload.php';

use Mews\Tsl\Point;

$client = new Point(new \Guzzle\Http\Client('http://www.tff.org/Default.aspx?pageId=198'));
$client->parse('div#ctl00_MPane_m_198_1890_ctnr_m_198_1890_Panel1');

echo $client->toTable('table_id', 'table_class');

/*
$html = '<table border="1">';
$html .= '<thead>';
$html .= '<tr>';

foreach ($client->getCols() as $col) {
    $html .= '<th>' . $col . '</th>';
}

$html .= '</tr>';
$html .= '</thead>';
$html .= '<tbody>';

foreach ($client->getRows() as $row) {
    $html .= '<tr>';
    foreach ($row as $item) {
        $html .= '<td>' . $item . '</td>';
    }
    $html .= '</tr>';
}

$html .= '</tbody>';
$html .= '<table>';

echo $html;
*/
