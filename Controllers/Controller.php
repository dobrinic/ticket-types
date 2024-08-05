<?php

namespace Controllers;

use Core\Database;

abstract class Controller
{
    protected Database $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }
}