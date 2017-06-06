<?php

namespace Tellaw\SunshineAdminBundle\Service;
use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Entities Manager
 */
class EntityService
{
    /**
     * Entities configuration
     * @var array
     */
    private $configuration;

    /**
     * Constructor
     *
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Provide the entity configuration for the form view
     *
     * @param string $entityName
     * @return array
     * @throws \Exception
     */
    public function getFormConfiguration($entityName)
    {
        return $this->getConfigurationByViewType($entityName, "form");
    }

    /**
     * Provide the entity configuration for the list view
     *
     * @param string $entityName
     * @return array
     * @throws \Exception
     */
    public function getListConfiguration($entityName)
    {
        return $this->getConfigurationByViewType($entityName, "list");
    }

    /**
     * Provide the entity configuration
     *
     * @param string $entityName
     * @return array
     */
    public function getConfiguration($entityName)
    {
        // On vérifie que l'entité existe
        if (!isset($this->configuration[$entityName])) {
            return false;
        }

        return $this->configuration[$entityName];
    }

    /**
     * Provide the entity configuration for a specific view
     *
     * @param string $entityName
     * @return array
     * @throws \Exception
     */
    protected function getConfigurationByViewType($entityName, $viewType)
    {
        // Getting the detailed configuration configuration
        $configuration = $this->getConfiguration($entityName);
        if (!$configuration) {
            throw new \Exception('Unknown entity');
        }

        $globalConfiguration = $configuration['attributes'];
        $detailedConfiguration = $configuration[$viewType]['fields'];

        $resultData = array();

        // For every configuration in detailled configuration
        foreach ($detailedConfiguration as $fieldName => $fieldDetailedConfiguration) {

            // Check if a configuration is related in global configuration and merge, overwise, just copy the detailed configuration
            // A field cannot be copyied if it is not declared in detailed configuration
            if (array_key_exists($fieldName, $globalConfiguration)) {
                $fieldGlobalConfiguration = $globalConfiguration[$fieldName];

                if (!is_array($fieldDetailedConfiguration)) {
                    $fieldDetailedConfiguration = array();
                }
                if (!is_array($fieldGlobalConfiguration)) {
                    $fieldGlobalConfiguration = array();
                }

                $resultData[$fieldName] = array_merge($fieldGlobalConfiguration, $fieldDetailedConfiguration);
            } else {
                $resultData[$fieldName] = $fieldDetailedConfiguration;
            }
        }

        foreach ($resultData as $fieldName => &$fieldConfiguration) {
            if (!isset($fieldConfiguration['type']) || empty($fieldConfiguration['type'])) {
                $fieldConfiguration['type'] = $this->guessEntityFieldType($configuration['configuration']['class'], $fieldName);
            }
        }

        return $resultData;
    }

    /**
     * Try to guess the type of an Doctrine entity field
     *
     * @param string $class
     * @param string $property
     * @return string
     * @throws \Exception
     */
    protected function guessEntityFieldType($class, $property)
    {
        $annotationReader = new AnnotationReader();

        $type = null;

        $typeDoctrine = null;
        $typeAssert = null;

        // If the type attribute doesn't exists in YML
        // the application will look for informations in
        // the doctrine annotations and ASSERT annotations

        // Get Annotations for attribute
        $reflectionProperty = new \ReflectionProperty($class, $property);
        $propertyAnnotations = $annotationReader->getPropertyAnnotations($reflectionProperty);

        foreach ($propertyAnnotations AS $annot) {
            if (get_class($annot) == "Symfony\\Component\\Validator\\Constraints\\Type") {
                $typeAssert = $annot->type;
            } elseif (get_class($annot) == "Doctrine\\ORM\\Mapping\\Column") {
                $typeDoctrine = $annot->type;
            }
        }

        // first look in asserts
        if ($typeAssert) {
            return $typeAssert;
            // secondly look in Doctrine annotations
        } else if ($typeDoctrine) {
            return $typeDoctrine;
        }

        throw new \Exception('Unable to guess the type for the ' . $property . ' property');
    }
}
