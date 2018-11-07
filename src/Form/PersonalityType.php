<?php

namespace App\Form;

use App\Entity\Personality;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;


class PersonalityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',TextType::class,  array(
                'label' => 'Тип (личности)',
            ))
            ->add('code', TextType::class,  array(
                'label' => 'Шифр',
            ))
            ->add('description', TextareaType::class,  array(
                'label' => 'Краткое описание',
            ))
            ->add('content', TextareaType::class,  array(
                'label' => 'Контент')
            )
            ->add('role')
            ->add('publish', CheckboxType::class,  array(
                'label' => 'Опубликовано', 'required' => false)
            )
            ->add('slide', FileType::class, array(
                 'label' => 'Слайд (.png, .jpg)', 'required' => false)
            )
            ->add('avatar', FileType::class, array(
                'label' => 'Аватар (.png, .jpg)', 'required' => false)
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personality::class,
        ]);
    }
}
