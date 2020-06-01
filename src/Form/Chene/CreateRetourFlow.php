<?php

namespace App\Form\Chene;

use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use App\Form\Chene\CreateRetourForm;

class CreateRetourFlow extends FormFlow {

    public function invalidateStepData($fromStepNumber) {
        // do nothing, this prevents us from deleting step data when user hits "back" button
    }

    protected function loadStepsConfig() {
        return [
            [
                'label' => 'Lieu de retour',
                'form_type' => CreateRetourForm::class,
            ],
            [
                'label' => 'Date de retour',
                'form_type' => CreateRetourForm::class
            ]
        ];
    }

}
