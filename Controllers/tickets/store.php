<?php

use Core\Database;
use Core\Session;
use Forms\TicketsForm;

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    abort();
}

$db = Database::get();
$form = new TicketsForm($db);

if ($form->notValid()) {
    Session::flash('errors', $form->errors());
    goBack();
}

$data = $form->getData();

$db->connection()->beginTransaction();

try {
    $sql = "INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)";
    $db->query($sql, [$data['name'], $data['email'], $data['phone']]);
    $customerId = $db->lastId();

    $sql = "INSERT INTO tickets (customer_id, ticket_type_id, quantity) VALUES (? ,?, ?)";
    $stmt = $db->connection()->prepare($sql);

    foreach ($data['tickets'] as $ticket) {
        $stmt->execute([$customerId, $ticket['type_id'], $ticket['quantity']]);
    }

    $db->connection()->commit();

    Session::flash('attributes', $data);
    redirect('/success');
} catch (PDOException $e) {
    $db->connection()->rollBack();
    // log the error
    echo "<p>There was an error processing your request. Please try again.</p>";
    throw $e;
}