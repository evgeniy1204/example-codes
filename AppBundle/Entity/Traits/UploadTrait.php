<?php

namespace BetaOmega\AppBundle\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

trait UploadTrait
{
	/**
     * @var file
     *
     * @Assert\File(
     *     maxSize="40M",
     *     mimeTypes = {"application/pdf",
     *                  "application/x-pdf",
     *                  "application/doc",
     *                  "image/png",
     *                  "image/jpg",
     *                  "image/jpeg"},
     *     mimeTypesMessage = "Please upload a valid file (.pdf, .doc, .png, .jpg, .jpeg)"
     *     )
     */
    protected $file;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * Avatar path by default if user nas not avatar
     */
    protected $defaultPath = "../bundles/app/img/avatars/avatar1.jpg";

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir() . '/' . $this->path;
    }


    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir() . '/' . $this->path;
    }

    public function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../../../web' . $this->getUploadDir();
    }

    public function preUpload()
    {
        if (null !== $this->file) {
            $fileOriginalName = $this->file->getClientOriginalName();
            $typeFile = $this->file->guessExtension();
            $generateName = uniqid();
            $this->path = $generateName.".".$typeFile;
        }

        return $this;
    }

    public function upload()
    {
        if (null === $this->file) {
            return;
        }

        if ($this->path) {
            if (file_exists($file = $this->getAbsolutePath())) {
                // unlink($file = $this->getAbsolutePath());
                unlink($file);
            }
        }
        $this->file->move($this->getUploadRootDir(), $this->path);

        // unset($this->file);
    }

    public function remove()
    {
        if ($this->path) {
            if (file_exists($file = $this->getAbsolutePath())) {
                unlink($file);
            }
        }

        return $this;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return User
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        if ($this->path != "" && $this->path != null) {
            return $this->path;
        }

        return $this->defaultPath;
    }

    public function getDefaultPath()
    {
        return $this->defaultPath;
    }
}