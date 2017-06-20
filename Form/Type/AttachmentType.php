<?php

namespace Tellaw\SunshineAdminBundle\Form\Type;

use AppBundle\Entity\Attachment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Vich\UploaderBundle\Form\Type\VichFileType;

/**
 */
class AttachmentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('attachment',
                VichFileType::class,
                [
                    'allow_delete' => false,
                    'mapping' => 'attachment',
                ]);
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
//    public function getParent()
//    {
//        return VichFileType::class;
//    }

    public function configureOptions(OptionsResolver $resolver)
    {
//        parent::configureOptions($resolver);
//        // makes it legal for FileType fields to have an image_property option
//        $resolver->setDefined(array('attachment_property'));
        $resolver->setDefaults(array(
            'data_class' => Attachment::class
        ));
    }

//    public function buildView(FormView $view, FormInterface $form, array $options)
//    {
//        if (isset($options['attachment_property'])) {
//            // this will be whatever class/entity is bound to your form (e.g. Media)
//            $parentData = $form->getParent()->getData();
//            /** @var File $fileUrl */
//            $fileUrl = null;
//            if (null !== $parentData) {
//                $accessor = PropertyAccess::createPropertyAccessor();
//                $fileUrl = $accessor->getValue($parentData, $options['attachment_property']);
//            }
//            // set an "image_url" variable that will be available when rendering this field
//            $view->vars['file_url'] = $fileUrl;
//        }
//    }

    public function getName()
    {
        return 'sunshine_attachment';
    }

}
