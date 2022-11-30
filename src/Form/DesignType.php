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
            ->add('titleFontSize', NumberType::class, [
                'help' => 'Font size in px, example - 50',
                'attr' => [
                    'placeholder' => '50'
                ]
            ])
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
            ->add('secondaryPageColor', ColorType::class, [
                'help' => 'Affects all secondary page elements'
            ])
            ->add('textColor', ColorType::class, [
                'help' => 'Affects the main text of the page'
            ])
            ->add('secondaryTextColor', ColorType::class, [
                'help' => 'Affects the links of the page'
            ])
            ->add('productTitle', TextType::class, [
                'help' => 'Controls the title of the product section',
                'attr' => [
                    'placeholder' => 'OUR COLLECTIONS'
                ]
            ])
            ->add('phoneNumber', NumberType::class)
            ->add('companyName', TextType::class)
            ->add('address', TextType::class)
            ->add('country', TextType::class)
            ->add('postalCode', TextType::class)
            ->add('copyright', TextType::class)
            ->add('productCount', TextType::class)
            ->add('currency', TextType::class, [
                'help' => 'Controls the currency for the entire site, example - €'
            ])
            ->add('Submit', SubmitType::class);
    }
}