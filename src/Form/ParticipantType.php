<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"", 'autocomplete'=>'off']
            ])
            ->add('prenoms', TextType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"", 'autocomplete'=>'off']
            ])
            ->add('contact', TelType::class,[
                'attr'=>['class'=>'form-control', 'placeholder'=>"Contact telephonique", 'autocomplete'=>'off']
            ])
            ->add('tickets', CollectionType::class,[
                'entry_type' => ParticiperType::class,
                'entry_options' => ['label'=>false],
                'allow_add' => true,
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
