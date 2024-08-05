<?php

use Core\Session;

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    abort();
}

$errors = Session::all('errors');
Session::unflash();

view('ticketTypes/create', [
    'errors' => $errors,
    'page_title' => 'Create new Ticket Type'
]);