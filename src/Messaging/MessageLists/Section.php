<?php

namespace Esyede\Wabot\Messaging\MessageLists;

use stdClass;

class Section
{
    private $sections = [];

    public function __construct()
    {
        $this->sections[0] = new stdClass;
    }

    public function setTitle($text)
    {
        $this->sections[0]->title = $text;
    }

    public function setRows(Row $rows)
    {
        $this->sections[0]->rows = $rows->all();
        return $this;
    }

    public function all()
    {
        return $this->sections;
    }
}