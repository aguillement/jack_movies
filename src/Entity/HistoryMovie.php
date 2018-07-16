<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryMovieRepository")
 */
class HistoryMovie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\History", inversedBy="historyMovies")
     */
    private $history;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Movie", inversedBy="historyMovies")
     */
    private $movie;

    /**
     * @Assert\Range(
     *      min = 0,
     *      max = 10,
     *      minMessage = "You must be at least {{ limit }} rate to enter",
     *      maxMessage = "You're rate can't be taller than {{ limit }} to enter"
     * )
     * @ORM\Column(type="integer", nullable=true)
     */
    private $note;

    public function getId()
    {
        return $this->id;
    }

    public function getHistory(): ?History
    {
        return $this->history;
    }

    public function setHistory(?History $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function getMovie(): ?Movie
    {
        return $this->movie;
    }

    public function setMovie(Movie $movie): self
    {
        $this->movie = $movie;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(?int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
