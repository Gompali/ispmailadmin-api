<?php

declare(strict_types=1);

namespace App\UI\Form;

use App\Domain\DTO\UpdateUserDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class UpdateUserType extends AbstractType
{
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver) : void
    {
        $resolver->setDefaults([
            'data_class' => UpdateUserDTO::class,
            'csrf_protection' => false,
        ]);
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) : void
    {
        $builder
            ->add('password', PasswordType::class, [
                    'required' => true,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'min' => 12,
                            'max' => 255,
                            'minMessage' => 'Your password name must be at least {{ limit }} characters long',
                            'maxMessage' => 'Your first name cannot be longer than {{ limit }} characters',
                        ]),
                    ],
                ]
            )
            ->add('quota', NumberType::class)
        ;
    }
}
