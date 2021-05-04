<?php

namespace App\Form;

use App\Entity\Participer;
use App\Entity\Ticket;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticiperType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,[
                'attr'=>['class' => 'control-label'],
                'choices' => [
                    'Invite' => "IN",
                    'Couple' => 'CP',
                    'Gratuit' => 'GR'
                ]
            ])
            //->add('montant')
            //->add('place')
            //->add('createdAt')
            //->add('updatedAt')
            //->add('user')
            ->add('ticket', EntityType::class,[
                'attr' => ['class' => 'control-label'],
                'class' => Ticket::class,
                'query_builder'=> function (EntityRepository $er) {
                    return $er->liste();
                },
                'choice_label' => 'code',
                'label' => 'Ticket'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participer::class,
        ]);
    }
}
