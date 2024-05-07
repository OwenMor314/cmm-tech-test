<?php

namespace App\Controller;

use App\Service\SurveyService;

class FormController extends Controller
{
    public function index()
    {
        $this->render('form.twig');
    }

    public function submit()
    {
        $postCode = $_POST['postcode'];
        $numBeds = $_POST['bedrooms'];
        $type = $_POST['type'];
        $service = new SurveyService($this->db(), $postCode, $numBeds, $type);
        $service->match();
        $service->pick(3);
        $matches = $service->results();
        $service->deductCredits();

        $this->render('results.twig', [
            'matchedCompanies'  => $matches,
        ]);
    }
}