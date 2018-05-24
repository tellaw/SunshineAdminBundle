<?php
namespace Tellaw\SunshineAdminBundle\Interfaces;

/**
 * Interface AttachmentInterface
 * @package Tellaw\SunshineAdminBundle\Interfaces
 */
interface AttachmentInterface
{
    /**
     * Attachment display name
     *
     * @return string
     */
    public function getOriginalName();

    /**
     * Attachment repository path
     *
     * @return mixed
     */
    public function getPath();

    /**
     * File content length
     *
     * @return mixed
     */
    public function getLength();

    /**
     * Name on file system
     *
     * @return mixed
     */
    public function getName();
}
