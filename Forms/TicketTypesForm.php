<?php

namespace Forms;

use Core\Database;

class TicketTypesForm implements RequestFormInterface
{
    private array $errors = [];
    private array $data = [];

    public function __construct(private readonly Database $db)
    {
        $this->validateType();
        $this->validatePrice();
    }

    private function validateType(): void
    {
        $val = trim($_POST['type'] ?? '');
        if (empty($val)) {
            $this->addError('type', 'Type is required');
        } elseif (strlen($val) < 2) {
            $this->addError('type', 'type must be at least 2 characters long');
        } elseif ($this->exists('ticket_types', $val)) {
            $this->addError('type', "Ticket type with name '$val' already exists");
        }

        $this->data['type'] = htmlspecialchars($val);
    }

    private function validatePrice(): void
    {
        $val = trim($_POST['price'] ?? '');
        if (empty($val)) {
            $this->addError('price', 'Price is required');
        } elseif (!is_numeric($val)) {
            $this->addError('price', 'Price must be a valid decimal number with up to 2 decimal places');
        }

        $this->data['price'] = number_format((float)$val, 2);
    }

    private function exists(string $table, $value): bool
    {
        $isUpdate = isset($_POST['_method']) && isset($_POST['id']) && $_POST['_method'] === 'PATCH';

        $sql = "SELECT COUNT(*) AS count FROM $table WHERE type = ?";

        if ($isUpdate) {
            $sql .= " AND id <> ?";
        }

        $stmt = $this->db->connection()->prepare($sql);
        $stmt->bindValue(1, $value);

        if ($isUpdate) {
            $stmt->bindValue(2, $_POST['id']);
        }

        $stmt->execute();

        $result = $stmt->fetch();

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