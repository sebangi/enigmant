<?php

namespace App\Form\General;

use App\Entity\General\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;

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

        if ($options['password'] == true) {
            $builder
                    ->add('currentPassword', PasswordType::class, [
                        'label' => 'Mot de passe actuel',
                        'mapped' => false,
                    ])
                    ->add('newPassword', RepeatedType::class, [
                        'type' => PasswordType::class,
                        'first_options' => array('label' => 'Mot de passe'),
                        'second_options' => array('label' => 'Répétez le mot de passe'),
                        // instead of being set onto the object directly,
                        // this is read and encoded in the controller
                        'invalid_message' => 'Les mots de passe ne sont pas identiques. Saisissez-les à nouveau !',
                        'mapped' => false,
                        'constraints' => [
                            new NotBlank([
                                'message' => 'Entrez un mot de passe',
                                    ]),
                            new Length([
                                'min' => 6,
                                'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                                // max length allowed by Symfony for security reasons
                                'max' => 4096,
                                    ])
            ]]);
        }


        if (( $options['general'] == true) || ($options['all'] == true )) {
            $builder
                    ->add('username', TextType::class, [
                        'invalid_message' => 'Votre identifiant doit contenir seulement des lettres et des chiffres et doit contenir entre 3 et 20 caractères. Il ne doit pas être déjà utilisé.',
                    ])
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
            ;
        }

        if (( $options['preferences'] == true) || ($options['all'] == true )) {
            $builder
                    ->add('visible', CheckboxType::class, [
                        'label' => false,
                        'attr' => [
                            'data-toggle' => "toggle",
                            'data-onstyle' => "dark",
                            'data-on' => "Oui",
                            'data-off' => "Non",
                        ],
                    ])
                    ->add('receptionInformationChasse', CheckboxType::class, [
                        'label' => false,
                        'attr' => [
                            'data-toggle' => "toggle",
                            'data-onstyle' => "dark",
                            'data-on' => "Oui",
                            'data-off' => "Non",
                        ],
                    ])
                    ->add('receptionInformationChene', CheckboxType::class, [
                        'label' => false,
                        'attr' => [
                            'data-toggle' => "toggle",
                            'data-onstyle' => "dark",
                            'data-on' => "Oui",
                            'data-off' => "Non",
                        ],
                    ])
                    ->add('receptionInformationGenerale', CheckboxType::class, [
                        'label' => false,
                        'attr' => [
                            'data-toggle' => "toggle",
                            'data-onstyle' => "dark",
                            'data-on' => "Oui",
                            'data-off' => "Non",
                        ],
                    ])
                    ->add('receptionInformationNouveau', CheckboxType::class, [
                        'label' => false,
                        'attr' => [
                            'data-toggle' => "toggle",
                            'data-onstyle' => "dark",
                            'data-on' => "Oui",
                            'data-off' => "Non",
                        ],
                    ])
            ;
        }

        if ($options['preferences'] == true) {
            $builder
                    ->add('valider', SubmitType::class, array(
                        'label' => 'Valider',
                        'attr' => array(
                            'class' => " mon-btn",
                            'formnovalidate' => 'formnovalidate'
                        ))
                    )
            ;
        }

        if (($options['general'] == true)||($options['password'] == true)) {
            $builder
                    ->add('cancel', SubmitType::class, array(
                        'label' => 'Annuler',
                        'attr' => array(
                            'class' => "btn-danger",
                            'formnovalidate' => 'formnovalidate'
                        ))
                    )
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => User::class,
            'all' => false,
            'password' => false,
            'create' => false,
            'general' => false,
            'preferences' => false,
            'translation_domain' => 'forms'
        ]);
    }

}
