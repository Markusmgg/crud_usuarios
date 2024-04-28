<?php

namespace App\Form;

use App\Entity\Usuarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; // Importa CheckboxType
use App\Entity\Ciudad;

class UsuariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dni')
            ->add('nombre')
            ->add('apellidos')
            ->add('ciudad', EntityType::class, [
                'class' => Ciudad::class,
                'choice_label' => 'nombre', 
            ])
            ->add('estado', CheckboxType::class, [ // Cambia a CheckboxType
                'label' => 'Estado',
                'required' => false, 
            ])
            ->add('direccion');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}