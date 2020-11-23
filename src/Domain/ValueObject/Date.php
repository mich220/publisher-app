<?php

namespace App\Domain\ValueObject;

class Date
{
    // todo: add if needed
    private string $date;

    public function __construct(string $date)
    {
        $this->create($date);
    }

    private function create(string $date)
    {
        $success = preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $date, $dateParts);

        if (!$success) {
            throw new \InvalidArgumentException('Invalid date format', $date);
        }
    }

    // todo: add getYear()...

    public function getDate(): string
    {
        return $this->date;
    }
}