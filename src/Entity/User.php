<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $password;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Profile", cascade={"persist", "remove"})
     */
    private $profile;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Watchlist", mappedBy="user", cascade={"persist", "remove"})
     */
    private $watchlist;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\History", mappedBy="user", cascade={"persist", "remove"})
     */
    private $history;

    /**
     * @ORM\Column(type="array", length=200, nullable=false)
     */
    private $roles;

    public function getId()
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

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(?Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }


    public function getRoles()
    {
        return $this->roles;
    }

    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }



    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


    public function getWatchlist(): ?Watchlist
    {
        return $this->watchlist;
    }

    public function setWatchlist(?Watchlist $watchlist): self
    {
        $this->watchlist = $watchlist;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $watchlist === null ? null : $this;
        if ($newUser !== $watchlist->getUser()) {
            $watchlist->setUser($newUser);
        }

        return $this;
    }

    public function getHistory(): ?History
    {
        return $this->history;
    }

    public function setHistory(?History $history): self
    {
        $this->history = $history;

        // set (or unset) the owning side of the relation if necessary
        $newUser = $history === null ? null : $this;
        if ($newUser !== $history->getUser()) {
            $history->setUser($newUser);
        }

        return $this;
    }
}
