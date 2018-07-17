<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 */
class Movie
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
     * @ORM\Column(type="string", length=30)
     */
    private $director;

    /**
     * @ORM\Column(type="datetime")
     */
    private $release_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $picture;

    /**
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="movies", cascade={"persist"})
     */
    private $categories;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $synopsis;

    private $pathPicture;

    /**
     * @return mixed
     */
    public function getPathPicture(): ?string
    {
        return $this->pathPicture;
    }

    /**
     * @param mixed $pathPicture
     */
    public function setPathPicture(?string $pathPicture): void
    {
        $this->pathPicture = $pathPicture;
    }
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoryMovie", mappedBy="movie")
     */
    private $historyMovies;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video_key;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $video_name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vote_average;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $vote_count;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->historyMovies = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getDirector(): ?string
    {
        return $this->director;
    }

    public function setDirector(string $director): self
    {
        $this->director = $director;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->release_date;
    }

    public function setReleaseDate(\DateTimeInterface $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(?string $synopsis): self
    {
        $this->synopsis = $synopsis;

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
            $historyMovie->setMovie($this);
        }

        return $this;
    }

    public function removeHistoryMovie(HistoryMovie $historyMovie): self
    {
        if ($this->historyMovies->contains($historyMovie)) {
            $this->historyMovies->removeElement($historyMovie);
            // set the owning side to null (unless already changed)
            if ($historyMovie->getMovie() === $this) {
                $historyMovie->setMovie(null);
            }
        }

        return $this;
    }

    public function getVideoKey(): ?string
    {
        return $this->video_key;
    }

    public function setVideoKey(?string $video_key): self
    {
        $this->video_key = $video_key;

        return $this;
    }

    public function getVideoName(): ?string
    {
        return $this->video_name;
    }

    public function setVideoName(?string $video_name): self
    {
        $this->video_name = $video_name;

        return $this;
    }

    public function getVoteAverage(): ?int
    {
        return $this->vote_average;
    }

    public function setVoteAverage(?int $vote_average): self
    {
        $this->vote_average = $vote_average;

        return $this;
    }

    public function getVoteCount(): ?int
    {
        return $this->vote_count;
    }

    public function setVoteCount(?int $vote_count): self
    {
        $this->vote_count = $vote_count;

        return $this;
    }
}
