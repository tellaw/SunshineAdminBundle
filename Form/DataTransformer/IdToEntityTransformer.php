<?php

namespace Tellaw\SunshineAdminBundle\Form\DataTransformer;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

/**
 * Transforme un id en entité.
 */
class IdToEntityTransformer implements DataTransformerInterface
{
    /**
     * Entity Manager.
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Le FQCN de l'entité à traiter.
     *
     * @var string
     */
    private $fqcn;

    /**
     * Méthode par défaut de recherche.
     *
     * @var string
     */
    private $method;

    /**
     * IdToEntityTransformer constructor.
     *
     * @param string                 $fqcn
     * @param EntityManagerInterface $entityManager
     * @param string                 $method
     */
    public function __construct(string $fqcn, EntityManagerInterface $entityManager, string $method = 'find')
    {
        $this->entityManager = $entityManager;
        $this->fqcn = $fqcn;
        $this->method = $method;
    }

    /**
     * Retourne l'ID d'une entité.
     *
     * @param mixed $entity
     *
     * @return mixed
     */
    public function transform($entity)
    {
        if (null === $entity) {
            return '';
        }

        if (is_string($entity)) {
            return $entity;
        }

        if ($entity instanceof ArrayCollection || $entity instanceof PersistentCollection) {
            $ids =  [];
            foreach($entity as $item) {
                $ids[]  = (string) $item->getId();
            }
            return $ids ;
        }

        return (string) $entity->getId();
    }

    /**
     * Retourne l'entité à partir d'un ID.
     *
     * @param mixed $id
     *
     * @return mixed|object|null
     */
    public function reverseTransform($id)
    {
        if (!$id) {
            return null;
        }

        if (is_array($id) && $this->method == 'find') {
            $method = 'getWhereIn';
        } else {
            $method = $this->method;
        }

        $entity = $this->entityManager
            ->getRepository($this->fqcn)
            ->$method($id);

        if (null === $entity) {
            throw new TransformationFailedException(sprintf('L\'entité "%s" n\'existe pas avec l\'id %s', $this->fqcn, $id));
        }

        return $entity;
    }
}
