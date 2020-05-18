<?php

namespace App\Form\Chene;

// src/MyCompany/MyBundle/Form/CreateVehicleForm.php
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use AppBundle\Form\DataTransformer\DateTimeTransformer;

class CreateReservationForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        switch ($options['flow_step']) {
            case 1:
                $builder
                        ->add('retraitRDV', CheckboxType::class,
                                [
                                    'attr' => ['class' => "custom-control-input only-one case_a_coche"],
                                    'label' => false,
                                    'label_attr' =>
                                    ['class' => 'custom-control-label',
                                        'name' => 'checkboxValidation'],
                                    'required' => false
                        ])
                        ->add('retraitDomicile', CheckboxType::class, [
                            'attr' => ['class' => "custom-control-input only-one"],
                            'label' => false,
                            'label_attr' =>
                            ['class' => 'custom-control-label',
                                'name' => 'checkboxValidation'],
                            'required' => false
                        ])
                        ->add('lieuRDV', TextareaType::class, [
                            'required' => false
                        ])
                ;
                break;
            case 2:

//                $builder
//                        ->add('dateRetrait', DateTimeType::class,
//                                [
//                                    'attr' => array(
//                                        'class' => 'form-control input-inline datetimepicker',
//                                        'data-provide' => 'datepicker',
//                                        'data-format' => 'dd-mm-yyyy HH:ii',
//                                    ),
//                        ])
//                ;
//         
//                       // This form type is not defined in the example.
                $builder
                        ->add('dateRetrait', TextType::class, array(
                            'required' => true,
                            'label' => 'form.label.datetime',
                            'translation_domain' => 'AppBundle',
                            'attr' => array(
                                'class' => 'form-control input-inline datepicker',
                                'data-provide' => 'datepicker',
                                'data-format' => 'dd-mm-yyyy HH:MM',
                            ),
                        ))
                ;
                $builder->get('dateRetrait')
                        ->addModelTransformer(new DateTimeTransformer());
                break;
        }
    }

    public function getBlockPrefix() {
        return 'createReservation';
    }

    public function getName() {
        return 'reservation_edit';
    }

}
