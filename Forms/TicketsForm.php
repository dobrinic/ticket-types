<?php

namespace Forms;

use Core\Database;

class TicketsForm implements RequestFormInterface
{
    private array $errors = [];
    private array $data = [];

    public function __construct(private readonly Database $db)
    {
        $this->validateName();
        $this->validateEmail();
        $this->validatePhone();
        $this->validateTickets();
    }

    private function validateName(): void
    {
        $val = trim($_POST['name'] ?? '');
        if (empty($val)) {
            $this->addError('name', 'Name is required');
        } elseif (strlen($val) < 2) {
            $this->addError('name', 'Name must be at least 2 characters long');
        }

        $this->data['name'] = htmlspecialchars($val);
    }

    private function validateEmail(): void
    {
        $val = trim($_POST['email'] ?? '');
        if (empty($val)) {
            $this->addError('email', 'Email is required');
        } elseif (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
            $this->addError('email', 'Email must be a valid email address');
        }

        $this->data['email'] = htmlspecialchars($val);
    }

    private function validatePhone(): void
    {
        $val = trim($_POST['phone'] ?? '');
        if (empty($val)) {
            $this->addError('phone', 'Phone number is required');
        } elseif (!preg_match('/^[0-9]{9,}$/', $val)) {
            $this->addError('phone', 'Phone number must be at least 9 digits long');
        }

        $this->data['phone'] = htmlspecialchars($val);
    }


    private function validateTickets(): void
    {
        $tickets = $_POST['tickets'] ?? [];
        if (empty($tickets)) {
            $this->addError('tickets', 'At least one ticket must be added');
        } else {
            foreach ($tickets as $index => $ticket) {
                $type = trim($ticket['type_id'] ?? '');
                $quantity = intval($ticket['quantity'] ?? 0);

                if (empty($type)) {
                    $this->addError("tickets[{$index}][type_id]", 'Ticket type is required');
                }elseif (!$this->exists('ticket_types', $type)) {
                    $this->addError("tickets[{$index}][type_id]", 'Ticket type does not exist');
                }

                if ($quantity < 1) {
                    $this->addError("tickets[{$index}][quantity]", 'Quantity must be at least 1');
                }

                $this->data["tickets"][$index]["type_id"] = htmlspecialchars($type);
                $this->data["tickets"][$index]["quantity"] = htmlspecialchars($quantity);
            }
        }
    }

    private function exists(string $table, $value): bool
    {
        $sql = "SELECT COUNT(*) AS count FROM $table WHERE id = ?";
        $result = $this->db->query($sql, [$value])->find();
        return $result['count'] > 0;
    }

    private function addError($key, $value): void
    {
        $this->errors[$key] = $value;
    }

    public function notValid(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function getData(): array
    {
        return $this->data;
    }
}