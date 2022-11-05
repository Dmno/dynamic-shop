<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $imageObject = $options['data']->getImage();
        $imageTitle = $imageObject ? $imageObject->getTitle() : "";

        $builder
            ->add('title', TextType::class)
            ->add('description', TextType::class)
            ->add('imageUpload', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Image',
                'attr' => [
                    'autocomplete' => 'off',
                    'class' => 'image-type-selector',
                    'id' => 'icon-select',
                    'placeholder' => $imageTitle
                ],
                'empty_data' => ''
            ])
            ->add('regularPrice', NumberType::class)
            ->add('memberPrice', NumberType::class)
            ->add('Submit', SubmitType::class);
    }
}