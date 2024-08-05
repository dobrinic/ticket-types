<?php

use Core\Session;

if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    abort();
}

$attributes = Session::all('attributes');

if (empty($attributes)) {
    redirect('/');
}

Session::unflash();

view('tickets/success', [
    'page_title' => 'Ticket Purchase Confirmation',
    'attributes' => $attributes
]);


