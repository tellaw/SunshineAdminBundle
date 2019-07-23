<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Tellaw\SunshineAdminBundle\Form\Type\ComboEntitySelectorType;
use Tellaw\SunshineAdminBundle\Service\EntityService;

class TinyMceController extends AbstractController
{
    /**
     * @Route("/tinymce/select-data/{configName}", name="sunshine_tinymce_data_selector", options={"expose"= true})
     * @param Request $request
     * @param string  $configName
     * @return array
     * @throws \InvalidArgumentException
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

//    /**
//     * @Template()
//     * @param Request $request
//     * @return array
//     * @throws \Exception
//     */
//    public function selectMediaAction(Request $request)
//    {
//        $formData = [
//            'data' => $this->getData($request),
//            'filter' => $request->get('dataFilter'),
//            'responsive' => $request->get('dataResponsive') == 1,
//        ];
//        $builder = $this->createFormBuilder($formData, [
//            'show_legend' => false,
//        ]);
//        $builder->add('data', 'eavmanager_media_browser', [
//            'family' => 'Image',
//        ]);
//
//        $filterConfig = $this->get('liip_imagine.filter.manager')->getFilterConfiguration()->all();
//        $choices = array_combine(array_keys($filterConfig), array_keys($filterConfig));
//        $builder->add('filter', 'choice', [
//            'choices' => $choices,
//        ]);
//
//        $builder->add('responsive', 'sidus_switch', [
//            'label' => 'Reponsive',
//            'required' => false,
//        ]);
//
//        $form = $builder->getForm();
//        $form->handleRequest($request);
//
//        return [
//            'form' => $form->createView(),
//        ];
//    }
//

}
