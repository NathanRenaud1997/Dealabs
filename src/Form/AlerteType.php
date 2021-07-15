<?php

namespace App\Form;

use App\Entity\Alerte;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AlerteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword')
            ->add('minimum_temperature')
            ->add('notify_by_email', CheckboxType::class, [
                'label' => "Recevoir une notification par e-mail une fois par jour regroupant tous les deals correspondant à cette alerte",
                'required' => false
            ])
            ->add('Créer une alerte', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Alerte::class,
        ]);
    }
}
