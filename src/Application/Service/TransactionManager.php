<?php

namespace App\Application\Service;

interface TransactionManager
{
    public function begin(): void;
    public function commit(): void;
    public function rollback(): void;
}