<?php

namespace GaylordP\FrontCommonBundle\Form;

use GaylordP\FrontCommonBundle\Form\Model\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        if (null !== $options['data']->getUser()) {
            $nameExtra = [
                'disabled' => true,
            ];
            $emailExtra = [
                'disabled' => true,
            ];
        }

        $builder
            ->add('name', null, [
                'label' => 'label.contact_name',
                'ico' => 'fas fa-user',
                'translation_domain' => 'front_common',
            ] + ($nameExtra ?? []))
            ->add('email', null, [
                'label' => 'label.email',
                'ico' => 'fas fa-envelope',
                'help' => 'email.help_privacy',
                'translation_domain' => 'user',
            ] + ($emailExtra ?? []))
            ->add('message', TextareaType::class, [
                'label' => 'label.message',
                'ico' => 'fas fa-pencil-alt',
                'attr' => [
                    'rows' => 10,
                ],
                'translation_domain' => 'front_common',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
