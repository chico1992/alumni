<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\CvRepository")
 */
class Cv
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Document", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\File(mimeTypes={ "application/pdf" })
     * @Assert\NotBlank()
     */
    private $document;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="cv", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDocument()
    {
        return $this->document;
    }

    public function setDocument($document): self
    {
        $this->document = $document;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
