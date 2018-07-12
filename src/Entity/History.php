<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 */
class History
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", inversedBy="history", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoryMovie", mappedBy="history", cascade={"persist", "remove"})
     */
    private $historyMovies;

    public function __construct()
    {
        $this->historyMovies = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|HistoryMovie[]
     */
    public function getHistoryMovies(): Collection
    {
        return $this->historyMovies;
    }

    public function addHistoryMovie(HistoryMovie $historyMovie): self
    {
        if (!$this->historyMovies->contains($historyMovie)) {
            $this->historyMovies[] = $historyMovie;
            $historyMovie->setHistory($this);
        }

        return $this;
    }

    public function removeHistoryMovie(HistoryMovie $historyMovie): self
    {
        if ($this->historyMovies->contains($historyMovie)) {
            $this->historyMovies->removeElement($historyMovie);
            // set the owning side to null (unless already changed)
            if ($historyMovie->getHistory() === $this) {
                $historyMovie->setHistory(null);
            }
        }

        return $this;
    }
}
