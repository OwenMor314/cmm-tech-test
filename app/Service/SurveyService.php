<?php

namespace App\Service;

use App\Service\Log;

class SurveyService
{
    private $db, $postcode, $numBeds, $type;
    public $matches = [];

    public function __construct(\PDO $db, string $postcode, int $numBeds, string $type) 
    {
        $this->db = $db;
        $this->postcode = $this->convertPostcode($postcode);
        $this->numBeds = $numBeds;
        $this->type = $type;
    }

    public function match(): void
    {
        $stmt = $this->db->prepare(
            "select * from company_matching_settings 
            inner join companies
            on company_matching_settings.company_id = companies.id
            where companies.active = 1 
            and companies.credits > 0
            and type = :type
            and postcodes like :postcode
            and bedrooms like :numBeds
            ");
        $stmt->execute([
            'type' => $this->type,
            'postcode' => '["'.$this->postcode.'"]',
            'numBeds' => '%"'.$this->numBeds.'"%'
        ]);
        $this->matches = $stmt->fetchAll();
    }

    public function pick(int $count): void
    {
        shuffle($this->matches);
        $this->matches = array_slice($this->matches, 0, $count);
    }

    public function results(): array
    {
        return $this->matches;
    }

    public function deductCredits()
    {
        $companiesWhoReactedZero = [];
        foreach($this->matches as $match) {
            if($match['credits'] == 1) {
                $companiesWhoReactedZero[] = $match;
            }
            $stmt = $this->db->prepare(
                "UPDATE companies 
                SET credits = credits - 1
                where id = :companyId
                ");
            $stmt->execute([
                'companyId' => $match['company_id'],
            ]);
        }

        $this->notifyZeroCompanies($companiesWhoReactedZero);
    }

    private function convertPostcode(string $postcode): string {
        //splits string before first number, e.g. 'cf11 6HP'=> [cf, 11 6HP]
        $pattern = '/(?=\d)/';
        $array = preg_split($pattern, $postcode, 2);
        return $array[0];
    }

    private function notifyZeroCompanies($companies):void {
        // create a log channel
        $log = new Log('CompanyZeroCredits', 'companyZeroCredits.log', 'Info');

        foreach($companies as $company) {
            $message = $company['name'].' just reached 0 credits.';
            $log->info($message);
        }
        
    }
}
