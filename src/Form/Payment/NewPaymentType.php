<?php

namespace App\Form\Payment;

use App\Domain\Command\Back\NewPaymentCommand;
use App\Entity\Adherent;
use App\Entity\Season;
use LogicException;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewPaymentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var Adherent $adherent */
        $adherent = $options['adherent'];
        /** @var Season $season */
        $season = $options['season'];

        $builder->add('mode', ChoiceType::class, [
            'mapped' => false,
            'placeholder' => 'form.newPayment.modePlaceholder',
            'choices' => [
                'enum.paymentType.CASH' => CashPaymentType::class,
                'enum.paymentType.CHECK' => CheckPaymentType::class,
                'enum.paymentType.HELLO_ASSO' => HelloAssoPaymentType::class,
                'enum.paymentType.TRANSFER' => TransferPaymentType::class,
                'enum.paymentType.ANCV' => AncvPaymentType::class,
                'enum.paymentType.PASS' => PassPaymentType::class,
            ],
        ]);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($adherent, $season) {
            /** @var string|null $data */
            $data = $event->getForm()->get('mode')->getData();

            $this->togglePaymentMode($event->getForm(), $adherent, $season, $data);
        });

        $builder->get('mode')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($adherent, $season) {
                $form = $event->getForm();

                if (null === $form->getParent()) {
                    throw new LogicException('invalid parent');
                }

                /** @var string|null $mode */
                $mode = $form->getData();
                $this->togglePaymentMode($form->getParent(), $adherent, $season, $mode);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['season', 'adherent']);
        $resolver->setAllowedTypes('season', Season::class);
        $resolver->setAllowedTypes('adherent', Adherent::class);

        $resolver->setDefaults([
            'data_class' => NewPaymentCommand::class,
            'label_format' => 'form.newPayment.%name%',
        ]);
    }

    private function togglePaymentMode(FormInterface $form, Adherent $adherent, Season $season, ?string $mode): void
    {
        if (!$mode) {
            $form->remove('payment');

            return;
        }

        $form->add('payment', $mode, [
            'adherent' => $adherent,
            'season' => $season,
            'label' => false,
        ]);
    }
}
