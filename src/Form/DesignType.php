<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class DesignType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $logoImageObject = $options['data']->getLogoImage();
        $logoImageTitle = $logoImageObject ? $logoImageObject->getTitle() : "";

        $backgroundImageObject = $options['data']->getBackgroundImage();
        $backgroundImageTitle = $backgroundImageObject ? $backgroundImageObject->getTitle() : "";

        $builder
            ->add('title', TextType::class)
            ->add('imageUpload', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Logo image',
                'attr' => [
                    'autocomplete' => 'off',
                    'class' => 'image-type-selector',
                    'id' => 'icon-select',
                    'placeholder' => $logoImageTitle
                ],
                'empty_data' => ''
            ])
            ->add('backgroundImageUpload', FileType::class, [
                'mapped' => false,
                'required' => false,
                'label' => 'Background image',
                'attr' => [
                    'autocomplete' => 'off',
                    'class' => 'image-type-selector',
                    'id' => 'icon-select',
                    'placeholder' => $backgroundImageTitle
                ],
                'empty_data' => ''
            ])
            ->add('pageColor', ColorType::class, [
                'help' => 'Affects the page background color'
            ])
            ->add('textColor', ColorType::class, [
                'help' => 'Affects the main text of the page'
            ])
            ->add('secondaryTextColor', ColorType::class, [
                'help' => 'Affects the links of the page'
            ])
            ->add('phoneNumber', NumberType::class)
            ->add('companyName', TextType::class)
            ->add('address', TextType::class)
            ->add('country', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('copyright', TextType::class)
            ->add('productCount', TextType::class)
            ->add('Submit', SubmitType::class);
    }
}