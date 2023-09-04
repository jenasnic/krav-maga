<?php

namespace App\Form\Content;

use App\Entity\Content\News;
use App\Form\Type\BulmaFileType;
use App\Form\Type\WysiwygType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Constraints\File;

class NewsType extends AbstractType
{
    public function __construct(protected RouterInterface $router)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('content', WysiwygType::class)
            ->add('details', WysiwygType::class, ['small_size' => true])
            ->add('active', CheckboxType::class, ['required' => true])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var News|null $news */
            $news = $event->getData();

            $fieldOptions = [
                'required' => false,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                    ]),
                ],
            ];

            if (null !== $news?->getPictureUrl()) {
                $fieldOptions['download_uri'] = $this->router->generate('app_news_picture', ['news' => $news->getId()]);
            }

            $form->add('pictureFile', BulmaFileType::class, $fieldOptions);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => News::class,
            'label_format' => 'form.news.%name%',
        ]);
    }
}
