<?php

namespace App\Form\Chene;

// src/MyCompany/MyBundle/Form/CreateVehicleFlow.php
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use App\Form\Chene\CreateReservationForm;

class CreateReservationFlow extends FormFlow {

    public function invalidateStepData($fromStepNumber) {
        // do nothing, this prevents us from deleting step data when user hits "back" button
    }

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
                'label' => 'Babioles',
                'form_type' => CreateReservationForm::class,
            ],
            [
                'label' => 'Validation',
                'form_type' => CreateReservationForm::class
            ]
        ];
    }

}
