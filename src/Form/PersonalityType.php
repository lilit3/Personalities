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
//        'attr' => array('class' => 'inp-field'),
        $builder
            ->add('title',TextType::class,  array(
                'label' => 'Заголовок',
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
            ->add('slidename', TextType::class,  array(
                'label' => 'Слайд',
            ))
            ->add('role')
            ->add('publish', CheckboxType::class,  array(
                'label' => 'Опубликовано', 'required' => false
            ))

//            ->add('slidename', FileType::class)
//            ->add('genericFile', VichFileType::class, [
//                'required' => false,
//                'allow_delete' => true,
//                'download_uri' => false,
//                'download_label' => function (Personality $personality) {
//                    return $personality->getTitle();
//                },
//            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Personality::class,
        ]);
    }
}
