<?php

namespace App\Form;

use App\Entity\Invitant;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InvitantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Nom de l'invitant", 'autocomplete'=>"off"],
                'label'=>"Nom"
            ])
            ->add('prenoms', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Prenoms de l'invitant", 'autocomplete'=>"off"],
                'label'=>"Prenoms"
            ])
            ->add('contact', TelType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Contact de l'invitant", 'autocomplete'=>"off"],
                'label'=>"Contact"
            ])
            ->add('user', EntityType::class,[
                'attr'=>['class'=>'form-control'],
                'class'=>User::class,
                'query_builder' => function(EntityRepository $er){
                    return $er->liste();
                },
                'choice_label'=>'username',
                'label'=>'Nom utilisateur'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Invitant::class,
        ]);
    }
}
