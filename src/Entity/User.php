<?php
// src/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Paladin\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */

class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Personality", mappedBy="author")
     */
    private $personalities;

    public function __construct()
    {
        parent::__construct();
        $this->personalities = new ArrayCollection();
        // your own logic
        //$this->setRoles(["ROLE_USER"]);
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
            $personality->setAuthor($this);
        }

        return $this;
    }

    public function removePersonality(Personality $personality): self
    {
        if ($this->personalities->contains($personality)) {
            $this->personalities->removeElement($personality);
            // set the owning side to null (unless already changed)
            if ($personality->getAuthor() === $this) {
                $personality->setAuthor(null);
            }
        }

        return $this;
    }
}
