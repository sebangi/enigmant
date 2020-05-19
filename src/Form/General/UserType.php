<?php

namespace App\Form\General;

use App\Entity\General\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        if ($options['create'] == true) {
            $builder
                    ->add('plainPassword', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'first_options' => array('label' => 'Mot de passe'),
                        'second_options' => array('label' => 'Répétez le mot de passe'),
                        'mapped' => false,
                    ]);
        }        
        
        $builder
        ->add('username', TextType::class)
        ->add('email', EmailType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci d\'entrer un e-mail',
                                ]),
                    ],
                    'required' => true,
                    'attr' => ['class' => 'form-control'],
                ])
        ->add('telephone', TelType::class, [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Merci d\'entrer votre numéro de téléphone (utile pour les locations)',
                                ]),
                    ],
                    'required' => true,
                    'attr' => ['class' => 'form-control'],
                ])
        ->add('prenom')                 
        ->add('nom')                  
        ->add('masque')                
        ->add('receptionInformationChasse')
        ->add('receptionInformationChene')  
        ->add('receptionInformationGenerale')  
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
            'create' => false,
            'translation_domain' => 'forms'
        ]);
    }

}
