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
use App\Form\DateTimeTransformer;

class CreateReservationForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        switch ($options['flow_step']) {
            case 1:
                $builder
                        ->add('retraitRDV', CheckboxType::class,
                                [
                                    'attr' => ['class' => "custom-control-input only-one case_a_cocher1"],
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
                            'required' => false,
                            'attr' => [
                                'placeholder' => 'Lieu proposé'
                            ]
                        ])
                ;
                break;
            case 2:
                $builder
                        ->add('dateRetrait', TextType::class, array(
                            'required' => true,
                            'label' => false,
                            'translation_domain' => 'AppBundle',
                            'attr' => array(
                                'class' => 'datetimepicker-input',
                                'data-target' => "#dateRetrait"
                            ),
                        ))
                ;
                $builder->get('dateRetrait')
                        ->addModelTransformer(new DateTimeTransformer());
                break;
            case 3:
                $builder
                        ->add('aBabiole', CheckboxType::class,
                                [
                                    'attr' => ['class' => "custom-control-input only-one case_a_cocher3"],
                                    'label' => false,
                                    'label_attr' =>
                                    ['class' => 'custom-control-label',
                                        'name' => 'checkboxValidation'],
                                    'required' => false
                                ])
                        ->add('aAucuneBabiole', CheckboxType::class,
                                [
                                    'attr' => ['class' => "custom-control-input only-one case_a_cocher4"],
                                    'label' => false,
                                    'label_attr' =>
                                    ['class' => 'custom-control-label',
                                        'name' => 'checkboxValidation'],
                                    'required' => false
                        ])
                        ->add('aPeutEtreBabiole', CheckboxType::class,
                                [
                                    'attr' => ['class' => "custom-control-input only-one case_a_cocher1"],
                                    'label' => false,
                                    'label_attr' =>
                                    ['class' => 'custom-control-label',
                                        'name' => 'checkboxValidation'],
                                    'required' => false
                        ])
                        ->add('aPasAssezBabiole', CheckboxType::class,
                                [
                                    'attr' => ['class' => "custom-control-input only-one case_a_cocher2"],
                                    'label' => false,
                                    'label_attr' =>
                                    ['class' => 'custom-control-label',
                                        'name' => 'checkboxValidation'],
                                    'required' => false
                        ]);
                break;
            case 4:
                $builder
                        ->add('contactOk', CheckboxType::class, [
                            'attr' => ['class' => "custom-control-input only-one"],
                            'label' => "Je valide mes coordonnées de contact",
                            'label_attr' =>
                            ['class' => 'custom-control-label text-danger',
                                'name' => 'checkboxValidation'],
                            'required' => false
                        ])
                ;
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
