<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RoleRepository")
 */
class Role
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $traits;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $color;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Personality", mappedBy="role")
     */
    private $personalities;

    public function __construct()
    {
        $this->personalities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTraits(): ?string
    {
        return $this->traits;
    }

    public function setTraits(?string $traits): self
    {
        $this->traits = $traits;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function __ToString()
    {
       return $this->title ? $this->title : 'New role';
    }

    /**
     * @return Collection|Personality[]
     */
    public function getPersonalities(): Collection
    {
        return $this->personalities;
    }

    public function addPersonality(Personality $personality): self
    {
        if (!$this->personalities->contains($personality)) {
            $this->personalities[] = $personality;
            $personality->setRole($this);
        }

        return $this;
    }

    public function removePersonality(Personality $personality): self
    {
        if ($this->personalities->contains($personality)) {
            $this->personalities->removeElement($personality);
            // set the owning side to null (unless already changed)
            if ($personality->getRole() === $this) {
                $personality->setRole(null);
            }
        }

        return $this;
    }
}
