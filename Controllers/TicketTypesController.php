<?php

namespace Controllers;

use Core\Database;
use Core\Session;
use Forms\TicketTypesForm;
use PDOException;

class TicketTypesController extends Controller
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

        $message = Session::all('message');
        Session::unflash();

        view('ticketTypes/index', [
            'page_title' => 'Ticket Types',
            'ticketTypes' => $ticketTypes,
            'message' => $message
        ]);
    }

    public function create(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "GET") {
            abort();
        }

        $errors = Session::all('errors');
        Session::unflash();

        view('ticketTypes/create', [
            'errors' => $errors,
            'page_title' => 'Create new Ticket Type'
        ]);
    }

    public function store(): void
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            abort();
        }

        $form = new TicketTypesForm($this->db);

        if ($form->notValid()) {
            Session::flash('errors', $form->errors());
            goBack();
        }

        $data = $form->getData();

        try {
            $sql = "INSERT INTO ticket_types (type, price) VALUES (?, ?)";
            $this->db->query($sql, [$data['type'], $data['price']]);

            Session::flash('message', [
                'type' => 'success',
                'message' => "Successfully created ticket type '{$data['type']}'"
            ]);
            redirect('/types');
        } catch (PDOException $e) {
            // log the error
            echo "<p>There was an error processing your request. Please try again.</p>";
            throw $e;
        }
    }

    public function edit(): void
    {
        if (!isset($_GET['id'])) {
            abort();
        }

        $ticketType = $this->db->query('SELECT * FROM ticket_types WHERE id = ?', [$_GET['id']])->findOrFail();

        $errors = Session::all('errors');
        Session::unflash();

        view('ticketTypes/edit', [
            'page_title' => "Edit Ticket Type {$ticketType['type']}",
            'ticketType' => $ticketType,
            'errors' => $errors
        ]);
    }

    public function update(): void
    {
        if (!isset($_POST['id'] ) || !isset($_POST['_method']) || $_POST['_method'] !== 'PATCH') {
            abort();
        }

        $ticketType = $this->db->query('SELECT * FROM ticket_types WHERE id = ?', [$_POST['id']])->findOrFail();

        $form = new TicketTypesForm($this->db);

        if ($form->notValid()) {
            Session::flash('errors', $form->errors());
            goBack();
        }

        $data = $form->getData();

        try {
            $sql = "UPDATE ticket_types SET type = ?, price = ? WHERE id = ?";
            $this->db->query($sql, [$data['type'], $data['price'], $ticketType['id']]);

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
    }

    public function destroy(): void
    {
        if (!isset($_POST['id']) || !isset($_POST['_method']) || $_POST['_method'] !== 'DELETE') {
            abort();
        }

        $ticketType = $this->db->query('SELECT * FROM ticket_types WHERE id = ?', [$_POST['id']])->findOrFail();

        try {
            $this->db->query('DELETE FROM ticket_types WHERE id = ?', [$ticketType['id']]);

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
    }
}