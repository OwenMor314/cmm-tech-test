<?php

namespace App\Service;

class CompanyMatcher
{
    private $db;
    private $matches = [];

    public function __construct(\PDO $db) 
    {
        $this->db = $db;
    }

    public function match()
    {
        
    }

    public function pick(int $count)
    {
        
    }

    public function results(): array
    {
        return $this->matches;
    }

    public function deductCredits()
    {
        
    }
}
