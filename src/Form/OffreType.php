<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('Contract', ChoiceType::class,[
                'choices' => [
                    'Apprentissage' => 'Apprentissage',
                    'CDD' => 'CDD'
                ]
            ])
            ->add('startDate')
            ->add('endDate')
            ->add('level',ChoiceType::class,[
                'choices' => [
                    'Bac + 1' => 'Bac + 1',
                    'Bac + 2' => 'Bac + 2'
                ],
            ])
            ->add('experienceYear',ChoiceType::class,[
                'choices' => [
                        '1' => '1',
                        '2' => '2',
                    ],
            ])

            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}
