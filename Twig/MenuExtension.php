<?php

namespace Tellaw\SunshineAdminBundle\Twig;

use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $securityChecker;

    /**
     * Constructor
     * @param AuthorizationCheckerInterface $securityChecker
     */
    public function __construct(AuthorizationCheckerInterface $securityChecker = null)
    {
        $this->securityChecker = $securityChecker;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions(): array
    {
        return array(
            new TwigFunction('isThisActivePage', array($this, 'isThisActivePage'), array()),
            new TwigFunction('isActivePageIsAChildPage', array($this, 'isActivePageIsAChildPage'), array()),
            new TwigFunction('isCustomPageEdit', array($this, 'isCustomPageEdit'), array()),
            new TwigFunction('isMenuItemVisible', array($this, 'isMenuItemVisible'), array()),
            new TwigFunction('getClass', array($this, 'getClass'), array())
        );
    }

    /**
     * @param $item
     * @param $pageId
     * @return bool
     */
    public function isThisActivePage($item, $pageId)
    {
        if (isset ($item["parameters"]["id"]) && $item["parameters"]["id"] == $pageId) {
            return true;
        } else if (isset ($item["entityName"]) && $item["entityName"] == $pageId) {
            return true;
        } else {
            return false;
        }
    }

    public function isActivePageIsAChildPage ($item, $pageType, $pageIdentifier)
    {
        if ( isset( $item["children"]) ) {
            return $this->isAChildPage( $item, $pageType, $pageIdentifier );
        } else {
            return false;
        }
    }

    private function isAChildPage ( $item, $pageType, $pageIdentifier )
    {

        if ( !isset( $item["children"]) ) {
            return false;
        }

        foreach ( $item["children"] as $item ) {
            if ( $item["type"] == "sunshine_page" && $item["parameters"]["id"] == $pageIdentifier ) {
                return true;
            } else if ($item["type"] == "sunshine_page_list" && $item["entityName"] == $pageIdentifier) {
                return true;
            } else if ($item["type"] == "custom_page" && $item["route"] == $pageIdentifier) {
                return true;
            } else if ( isset( $item["children"] ) ) {
                $this->isAChildPage( $item, $pageType, $pageIdentifier );
            }
        }

        return false;

    }

    /**
     * @param $item
     * @param $pageType
     * @param $pageIdentifier
     * @return bool
     */
    public function isCustomPageEdit ($item, $pageType, $pageIdentifier)
    {
        $pageIdentifier = substr($pageIdentifier, strrpos($pageIdentifier, "_") + 1);
        $pageId = isset ($item["parameters"]["id"]) ? $item["parameters"]["id"] : null;

        if ($pageType === 'custom_page' && !is_null($pageId) && strcasecmp($pageId, $pageIdentifier) === 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vérifie si l'utilisateur loggué peut voir l'élément de menu
     *
     * @param array $item élément du menu
     * @return boolean
     */
    public function isMenuItemVisible($item)
    {
        if (empty($item['security'])) {
            return true;
        }

        $security = $item['security'];

        // On vérifie dans un premier temps si l'utilisateur possède la permission sur l'entité configurée
        if (!empty($security['permissions']) && !empty($security['entity'])) {
            foreach ($security['permissions'] as $permission) {
                if ($this->isGranted($permission, $security['entity'])) {

                    return true;
                }
            }
        }
        // On vérifie si le role de l'utilisateur est autorisé
        if (!empty($security['roles'])) {
            foreach ($security['roles'] as $role) {
                if ($this->isGranted($role)) {

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Vérifie les droits d'accès de l'utilisateur loggué
     *
     * @param string $role
     * @param mixed $object
     * @return bool
     */
    public function isGranted($role, $object = null)
    {
        if (null === $this->securityChecker) {
            return false;
        }

        try {
            return $this->securityChecker->isGranted($role, $object);
        } catch (AuthenticationCredentialsNotFoundException $e) {
            return false;
        }
    }

    /**
     * Retourne le nom de la classe
     *
     * @param $object
     * @return string
     * @throws \ReflectionException
     */
    public function getClass($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }
}
