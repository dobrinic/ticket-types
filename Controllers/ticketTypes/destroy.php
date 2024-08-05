<?php

use Core\Database;
use Core\Session;

if (!isset($_POST['id']) || !isset($_POST['_method']) || $_POST['_method'] !== 'DELETE') {
    abort();
}

$db = Database::get();

$ticketType = $db->query('SELECT * FROM ticket_types WHERE id = ?', [$_POST['id']])->findOrFail();

try {
    $db->query('DELETE FROM ticket_types WHERE id = ?', [$ticketType['id']]);

} catch (PDOException $e) {
    if($e->errorInfo[1] === 1451) {
        Session::flash('message', [
            'type' => 'danger',
            'message' => "Cannot delete ticket type '{$ticketType['type']}' because it is in use"
        ]);
        goBack();
    };

    echo "<p>There was an error processing your request. Please try again.</p>";
    throw $e;
}

Session::flash('message',  [
    'type' => 'success',
    'message' => "Successfully deleted ticket type '{$ticketType['type']}'"
]);
redirect('/types');

