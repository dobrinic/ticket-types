<?php

use Core\Database;
use Core\Session;

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    abort();
}

$ticketTypes = Database::get()->query('SELECT * FROM ticket_types')->all();
$errors = Session::all('errors');
Session::unflash();

view('tickets/index', [
    'ticketTypes' => $ticketTypes,
    'page_title' => 'Ticket Shopping',
    'errors' => $errors
]);


