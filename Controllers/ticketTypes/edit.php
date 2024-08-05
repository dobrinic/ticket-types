<?php

use Core\Database;
use Core\Session;

if (!isset($_GET['id'])) {
    abort();
}

$ticketType = Database::get()->query('SELECT * FROM ticket_types WHERE id = ?', [$_GET['id']])->findOrFail();

$errors = Session::all('errors');
Session::unflash();

view('ticketTypes/edit', [
    'page_title' => "Edit Ticket Type {$ticketType['type']}",
    'ticketType' => $ticketType,
    'errors' => $errors
]);


