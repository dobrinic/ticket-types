<?php

use Core\Database;
use Core\Session;

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    abort();
}

$ticketTypes = Database::get()->query('SELECT * FROM ticket_types')->all();

$message = Session::all('message');
Session::unflash();

view('ticketTypes/index', [
    'page_title' => 'Ticket Types',
    'ticketTypes' => $ticketTypes,
    'message' => $message ?? null
]);


