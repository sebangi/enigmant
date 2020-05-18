<?php

namespace App\Form\Chene;

// src/MyCompany/MyBundle/Form/CreateVehicleFlow.php
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use App\Form\Chene\CreateReservationForm;

class CreateReservationFlow extends FormFlow {

    protected function loadStepsConfig() {
        return [
            [
                'label' => 'Lieu de retrait',
                'form_type' => CreateReservationForm::class,
            ],
            [
                'label' => 'Date de retrait',
                'form_type' => CreateReservationForm::class
            ],
            [
                'label' => 'Contact',
            ],
            [
                'label' => 'Validation',
            ],
            [
                'label' => 'Confirmation',
            ]
        ];
    }

}
