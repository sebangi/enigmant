<?php

namespace App\Form\General;

use App\Entity\General\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
        ->add('username')
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'label' => false,
            'attr' => ['class' => 'largerCheckbox'],
            'constraints' => [
                new IsTrue([
                    'message' => 'Vous devez accepter les conditions.',
                ]),
            ]])
        ->add('plainPassword', RepeatedType::class, [
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
                ]])
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
                    'required' => true,
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
            'translation_domain' => 'forms'
        ]);
    }

}
