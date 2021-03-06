<?php
/**
 * Created by PhpStorm.
 * User: stefankamsker
 * Date: 02.11.18
 * Time: 14:36
 */

namespace UniteCMS\CoreBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UniteCMSCoreTypeExtension extends AbstractTypeExtension
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // add required validation dynamically
        if (isset($options['not_empty']) && $options['not_empty']) {
            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                if($event->getForm()->isEmpty()) {
                    $event->getForm()->addError(new FormError('This value should not be blank.'));
                }
            });
        }

    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        // pass description to template
        if (isset($options['description'])) {
            $view->vars['description'] = $options['description'];
        }

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined('description');
        $resolver->setDefined('default');
        $resolver->setDefined('not_empty');

        // If not_empty is set, also set the required option to true
        $resolver->setNormalizer('required', function(Options $options, $value){
            return $options->offsetExists('not_empty') && $options->offsetGet('not_empty') ? true : $value;
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return FormType::class;
    }

}