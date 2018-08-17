<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email", message="Email already taken")
 * @UniqueEntity(fields="username", message="Username already taken")
 */
class User implements UserInterface //, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string" , length=36)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creationDate;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     */
    private $roles;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Post", mappedBy="author", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Conversation", mappedBy="users")
     */
    private $conversations;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\VisibilityGroup")
     */
    private $visibilityGroups;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cv", cascade={"persist", "remove"})
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $cv;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Document", cascade={"persist", "remove"})
     * @Assert\File(mimeTypes={ "image/*" })
     */
    private $profilePicture;

    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->groups = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->conversations = new ArrayCollection();
        $this->visibilityGroups = new ArrayCollection();
        $this->creationDate = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }


    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    /**
     * Get the value of role
     * @return Role[]
     */ 
    public function getRoles(): array
    {
        
        return array_map('strval',$this->roles->toArray());
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
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->contains($post)) {
            $this->posts->removeElement($post);
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
            $conversation->addUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->contains($conversation)) {
            $this->conversations->removeElement($conversation);
            $conversation->removeUser($this);
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

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function setProfilePicture($profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getSalt(){
        return null;
    }

    public function eraseCredentials()
    {
        return null;

    }

    // /** @see \Serializable::serialize() */
    // public function serialize()
    // {
    //     return serialize(array(
    //         $this->id,
    //         $this->username,
    //         $this->firstname,
    //         $this->lastname,
    //         $this->profilePicture,
    //         // see section on salt below
    //         // $this->salt,
    //     ));
    // }

    // /** @see \Serializable::unserialize() */
    // public function unserialize($serialized)
    // {
    //     list (
    //         $this->id,
    //         $this->username,
    //         $this->firstname,
    //         $this->lastname,
    //         $this->profilePicture,
    //         // see section on salt below
    //         // $this->salt
    //     ) = unserialize($serialized, array('allowed_classes' => false));
    // }
}
