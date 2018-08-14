<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvitationRepository")
 */
class Invitation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string" , length=36)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\VisibilityGroup")
     */
    private $visibilityGroups;

    

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->visibilityGroups = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
        }

        return $this;
    }

    /**
     * @return Collection|VisibilityGroup[]
     */
    public function getVisibilityGroups(): Collection
    {
        return $this->visibilityGroups;
    }

    public function addVisibilityGroup(VisibilityGroup $visibilityGroup): self
    {
        if (!$this->visibilityGroups->contains($visibilityGroup)) {
            $this->visibilityGroups[] = $visibilityGroup;
        }

        return $this;
    }

    public function removeVisibilityGroup(VisibilityGroup $visibilityGroup): self
    {
        if ($this->visibilityGroups->contains($visibilityGroup)) {
            $this->visibilityGroups->removeElement($visibilityGroup);
        }

        return $this;
    }

}
