<?php

namespace App\Form\General;

use App\Entity\General\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserPasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        if ($options['require_current_password'] == true) {
            $builder
                    ->add('currentPassword', PasswordType::class, [
                        'label' => 'Mot de passe actuel',
                        'mapped' => false,
            ]);
        }

        $builder
                ->add('plainPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => array('label' => 'Nouveau mot de passe'),
                    'second_options' => array('label' => 'Répétez le nouveau mot de passe'),
                    'mapped' => false,
                ])
                ->add('save', SubmitType::class, array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-block btn-lg btn-success')));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'require_current_password' => false,
        ]);
    }

}
