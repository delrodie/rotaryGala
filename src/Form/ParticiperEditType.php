<?php

namespace App\Form;

use App\Entity\Participer;
use App\Entity\Ticket;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticiperEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class,[
                'attr'=>['class' => 'form-control', 'readonly'=>'readonly', 'disabled'=>true],
                'choices' => [
                    'Invite' => "IN",
                    'Couple' => 'CP',
                    'Gratuit' => 'GR'
                ]
            ])
            ->add('montant', TextType::class,['attr'=>['class' => 'form-control', 'readonly'=>'readonly']])
            ->add('place', TextType::class,['attr'=>['class' => 'form-control', 'autocomplete'=>'off']])
            //->add('createdAt')
            //->add('updatedAt')
            //->add('user')
            ->add('ticket', null,[
                'attr' => ['class' => 'form-control', 'readonly'=>true, 'disabled'=>true],
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
