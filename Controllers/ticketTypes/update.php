<?php

use Core\Database;
use Core\Session;
use Forms\TicketTypesForm;

if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
    abort();
}

$db = Database::get();
$ticketType = $db->query('SELECT * FROM ticket_types WHERE id = ?', [$_POST['id']])->findOrFail();

$form = new TicketTypesForm($db);

if ($form->notValid()) {
    Session::flash('errors', $form->errors());
    goBack();
}

$data = $form->getData();

try {
    $sql = "UPDATE ticket_types SET type = ?, price = ? WHERE id = ?";
    $db->query($sql, [$data['type'], $data['price'], $ticketType['id']]);

    Session::flash('message', [
        'type' => 'success',
        'message' => "Successfully updated ticket type '{$data['type']}'"
    ]);
    redirect('/types');
} catch (PDOException $e) {
    // log the error
    echo "<p>There was an error processing your request. Please try again.</p>";
    throw $e;
}
