<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity()
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="string" , length=36)
     * @Groups({"comment"})
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"comment"})
     */
    private $creationDate;

    /**
     * @ORM\Column(type="text")
     * @Groups({"comment"})
     * @Assert\NotBlank()
     */
    private $content;

    /**
     * @ORM\Column(type="boolean")
     */
    private $flag;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comment"})
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Post", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"comment"})
     */
    private $post;
  
 
    public function __construct()

    {
        $this->creationDate = new \DateTime();
        $this->flag = false;
    }


    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getFlag(): ?bool
    {
        return $this->flag;
    }

    public function setFlag(bool $flag): self
    {
        $this->flag = $flag;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
