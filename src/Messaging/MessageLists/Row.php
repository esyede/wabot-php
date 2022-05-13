<?php

namespace Esyede\Wabot\Messaging\MessageLists;

use stdClass;

class Row
{
    private $rows = [];
    private $idCounter = 0;

    public function add($title, $description)
    {
        $row = new stdClass;
        $row->title = $title;
        $row->description = $description;
        $row->rowId = $this->idCounter;

        $this->rows[$this->idCounter] = $rows;
        $this->idCounter++;

        return $this;
    }

    public function all()
    {
        return $this->rows;
    }
}