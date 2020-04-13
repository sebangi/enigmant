<?php

namespace App\Form\Chene;

use App\Entity\Chene\JeuEnChene;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JeuEnCheneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('intitule')
            ->add('commentaire')
            ->add('disponible')
            ->add('difficulteObservation')
            ->add('difficulteRaisonnement')
            ->add('tempsLocation', ChoiceType::class, [
                'choices' => $this->getChoix()
            ])
            ->add('prix')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JeuEnChene::class,
            'translation_domain' => 'forms'
        ]);
    }
    
    private function getChoix() {
        $choix = JeuEnChene::codeLocation;
        
        $output = [];
        foreach ( $choix as $k => $v )
        {
            $output[$v] = $k;
        }
        
        return $output;
    }
}
