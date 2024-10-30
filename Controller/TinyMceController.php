<?php

namespace Tellaw\SunshineAdminBundle\Controller;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tellaw\SunshineAdminBundle\Form\Type\ComboEntitySelectorType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\ORM\EntityManagerInterface;

class TinyMceController extends AbstractController
{
    protected EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, ParameterBagInterface $parameterBag)
    {
        $this->em = $em;
        $this->parameterBag = $parameterBag;
    }

    /**
     * @Route("/tinymce/select-data/{configName}", name="sunshine_tinymce_data_selector", options={"expose"= true})
     * @return Response
     */
    public function selectDataAction(Request $request, string $configName = '')
    {
        $formOptions = [];
        $selectorConfig = $this->parameterBag->get('tellaw_sunshine_admin.tinymce');
        if (array_key_exists($configName, $selectorConfig)) {
            $formOptions = $selectorConfig[$configName];
        }

        if ($request->query->has('family') && $request->query->has('dataId')) {
            $family = $request->query->get('family');
            $formOptions['data'] = [
                'family' => $family,
                'entity' => $this->getEntity($request, $family),
            ];
        }

        $formData = $request->request->all('form');

        if (is_array($formData)) {
            array_walk_recursive($formData, function (&$value) {
                if (is_array($value)) {
                    $value = json_encode($value); 
                }
            });
        }

        $builder = $this->createFormBuilder($formData, ['show_legend' => false]);
        $builder->setAction($this->generateUrl('sunshine_tinymce_data_selector', ['configName' => $configName]));
        $formOptions['label'] = false;
        $builder->add('comboEntitySelector', ComboEntitySelectorType::class, $formOptions);

        $form = $builder->getForm();
        $form->handleRequest($request);

        return $this->render(
            '@TellawSunshineAdmin/tinymce/select'.ucfirst($configName).'.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }

    /**
     * @Route("/tinymce/select-form", name="sunshine_tinymce_leadsfactory_form_selector", options={"expose"= true})
     * @return Response
     */
    public function selectLeadsfactoryFormAction(Request $request)
    {
        $dataId = $request->query->get('dataId', null);

        $builder = $this->createFormBuilder();
        $builder->add(
            'leadsfactory-form-id-input',
            TextType::class,
            [
                'label' => 'Identifiant formulaire',
                'data' => $dataId
            ]
        );

        $form = $builder->getForm();

        return $this->render(
            '@TellawSunshineAdmin/tinymce/selectForm.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @return DataInterface|null
     * @throws \InvalidArgumentException
     */
    protected function getEntity(Request $request, $family)
    {
        $data = null;
        if ($request->query->has('dataId')) {
            $data = $this->em->getRepository('App:' . $family)
                ->getByDataId($request->query->get('dataId'));
        }

        return $data;
    }
}
