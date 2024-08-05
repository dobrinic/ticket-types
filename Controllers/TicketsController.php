<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Forms\TicketsForm;
use PDOException;

class TicketsController extends Controller
{
    public function __construct(protected Database $db)
    {
        parent::__construct($db);
    }

    public function index(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "GET") {
            abort();
        }

        $ticketTypes = $this->db->query('SELECT * FROM ticket_types')->all();
        $errors = Session::all('errors');
        Session::unflash();

        view('tickets/index', [
            'ticketTypes' => $ticketTypes,
            'page_title' => 'Ticket Shopping',
            'errors' => $errors
        ]);
    }

    public function store(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            abort();
        }

        $form = new TicketsForm($this->db);

        if ($form->notValid()) {
            Session::flash('errors', $form->errors());
            goBack();
        }

        $data = $form->getData();

        $this->db->connection()->beginTransaction();

        try {
            $sql = "INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)";
            $this->db->query($sql, [$data['name'], $data['email'], $data['phone']]);
            $customerId = $this->db->lastId();

            $sql = "INSERT INTO tickets (customer_id, ticket_type_id, quantity) VALUES (? ,?, ?)";
            $stmt = $this->db->connection()->prepare($sql);

            foreach ($data['tickets'] as $ticket) {
                $stmt->execute([$customerId, $ticket['type_id'], $ticket['quantity']]);
            }

            $this->db->connection()->commit();

            Session::flash('attributes', $data);
            redirect('/success');
        } catch (PDOException $e) {
            $this->db->connection()->rollBack();
            // log the error
            echo "<p>There was an error processing your request. Please try again.</p>";
            throw $e;
        }
    }

    public function success(): void
    {
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
    }
}