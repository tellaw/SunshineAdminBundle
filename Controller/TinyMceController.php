<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tellaw\SunshineAdminBundle\Form\Type\ComboEntitySelectorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class TinyMceController extends AbstractController
{
    /**
     * @Route("/tinymce/select-data/{configName}", name="sunshine_tinymce_data_selector", options={"expose"= true})
     * @param Request $request
     * @param string $configName
     * @return Response
     */
    public function selectDataAction(Request $request, string $configName = '')
    {
        $formOptions = [];
        $selectorConfig = $this->getParameter('tellaw_sunshine_admin.tinymce');
        if (array_key_exists($configName, $selectorConfig)) {
            $formOptions = $selectorConfig[$configName];
        }

        $formData = $request->request->get('form');

        $builder = $this->createFormBuilder($formData, ['show_legend' => false]);
        $builder->setAction($this->generateUrl('sunshine_tinymce_data_selector', ['configName' => $configName]));
        $formOptions['label'] = false;
        $builder->add('comboEntitySelector', ComboEntitySelectorType::class, $formOptions);

        $form = $builder->getForm();
        $form->handleRequest($request);

        return $this->render(
            '@sunshine/tinymce/select'.ucfirst($configName).'.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/tinymce/select-form", name="sunshine_tinymce_leadsfactory_form_selector", options={"expose"= true})
     *
     * @return Response
     */
    public function selectLeadsfactoryFormAction()
    {
        $builder = $this->createFormBuilder(null, ['show_legend' => false]);
        $builder->add(
            'leadsfactory-form-id-input',
            TextType::class,
            [
                'label' => 'Identifiant formulaire'
            ]
        );

        $form = $builder->getForm();

        return $this->render(
            '@sunshine/tinymce/selectForm.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
