<?php

namespace Mews\Tsl;
use Guzzle\Http\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;


/**
 * Class Point
 * @package Mews\Tsl
 * @author Muharrem ERİN <me@mewebstudio.com>
 * @licence MIT
 */
class Point
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var \Guzzle\Http\EntityBodyInterface|string
     */
    protected $body;

    /**
     * @var \Symfony\Component\DomCrawler\Crawler
     */
    protected $crawler;

    /**
     * @var array
     */
    protected $cols = [];

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * Point constructor.
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->body = $this->client->get()->send()->getBody(true);
    }

    /**
     * @param string $element
     */
    public function parse($element)
    {
        $this->crawler = new Crawler($this->body);

        $this->crawler->filter($element)->filter('tr')->each(function (Crawler $row, $i) {
            $row->filter('td')->each(function (Crawler $col, $j) use ($i) {
                if ($i == 0) {
                    $this->cols[] = $col->text();
                } else {
                    if ($j == 0) {
                        $this->rows[$i][] = preg_replace('/' . $i . './', '', $col->text());
                    } else {
                        $this->rows[$i][] = $col->text();
                    }
                }
            });
        });
    }

    /**
     * @return array
     */
    public function getCols()
    {
        return $this->cols;
    }

    /**
     * @return array
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * @param null $id
     * @param null $class
     * @return string
     */
    public function toTable($id = null, $class = null)
    {
        $html = '<table' . ($id ? ' id="' . $id . '"' : null) . ($class ? ' class="' . $class . '"' : null) . '>';
        $html .= '<thead>';
        $html .= '<tr>';

        foreach ($this->getCols() as $col) {
            $html .= '<th>' . $col . '</th>';
        }

        $html .= '</tr>';
        $html .= '</thead>';
        $html .= '<tbody>';

        foreach ($this->getRows() as $row) {
            $html .= '<tr>';
            foreach ($row as $item) {
                $html .= '<td>' . $item . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody>';
        $html .= '<table>';

        return $html;
    }
}
