<?php

namespace App\Form;

use App\Entity\Usuarios;
use App\Entity\Ciudades; 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class Usuarios1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dni')
            ->add('nombre')
            ->add('apellidos')
            ->add('ciudades', EntityType::class, [ 
                'class' => Ciudades::class,
                'choice_label' => 'nombre', 
                'attr' => ['class' => 'form-control'],
            ])
            ->add('direccion');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}
