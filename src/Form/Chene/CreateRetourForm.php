<?php

namespace App\Form\Chene;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use App\Form\DateTimeTransformer;

class CreateRetourForm extends AbstractType {
    
    public function buildForm(FormBuilderInterface $builder, array $options) {
        switch ($options['flow_step']) {
            case 1:
                $builder
                        ->add('retourRDV', CheckboxType::class,
                                [
                                    'attr' => ['class' => "custom-control-input only-one case_a_cocher1"],
                                    'label' => false,
                                    'label_attr' =>
                                    ['class' => 'custom-control-label',
                                        'name' => 'checkboxValidation'],
                                    'required' => false
                        ])
                        ->add('retourDomicile', CheckboxType::class, [
                            'attr' => ['class' => "custom-control-input only-one"],
                            'label' => false,
                            'label_attr' =>
                            ['class' => 'custom-control-label',
                                'name' => 'checkboxValidation'],
                            'required' => false
                        ])
                        ->add('lieuRetourRDV', TextareaType::class, [
                            'required' => false,
                            'attr' => [
                                'placeholder' => 'Lieu proposé'
                            ]
                        ])
                ;
                break;
            case 2:
                $builder
                        ->add('dateRendu', TextType::class, array(
                            'required' => true,
                            'label' => false,
                            'translation_domain' => 'AppBundle',
                            'attr' => array(
                                'class' => 'datetimepicker-input',
                                'data-target' => "#dateRendu"
                            ),
                        ))
                ;
                $builder->get('dateRendu')
                        ->addModelTransformer(new DateTimeTransformer());
                break;            
        }
    }

    public function getBlockPrefix() {
        return 'createRetour';
    }

    public function getName() {
        return 'retour_edit';
    }

}
