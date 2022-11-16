<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class LoginType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', TextType::class, [
                'attr' => array(
                    'autofocus' => true
                ),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an email to login',
                    ]),
                    new Email(array(
                        'message' => 'Please enter an email'
                    ))
                ]
            ])
            ->add('password', PasswordType::class, [
                'mapped' => false,
                'label' => "Password",
                'attr' => array(
                    'autocomplete' => 'off',
                ),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_token_id'   => 'form_intention',
        ]);
    }
}