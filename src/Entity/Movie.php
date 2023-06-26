<?php

namespace App\Entity;

class Movie{
    public function __construct(
        private string $title,
        private string $resume,
        private \DateTime $released,
        private int $duree,
        private ?int $id=null
    ){}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}
	
	/**
	 * @param string $title 
	 * @return self
	 */
	public function setTitle(string $title): self {
		$this->title = $title;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getResume(): string {
		return $this->resume;
	}
	
	/**
	 * @param string $resume 
	 * @return self
	 */
	public function setResume(string $resume): self {
		$this->resume = $resume;
		return $this;
	}
	
	/**
	 * @return \DateTime
	 */
	public function getReleased():\DateTime {
		return $this->released;
	}
	
	/**
	 * @param \DateTime $released 
	 * @return self
	 */
	public function setReleased(\DateTime $released): self {
		$this->released = $released;
		return $this;
	}
	
	/**
	 * @return int
	 */
	public function getDuree(): int {
		return $this->duree;
	}
	
	/**
	 * @param int $duree 
	 * @return self
	 */
	public function setDuree(int $duree): self {
		$this->duree = $duree;
		return $this;
	}
	
	/**
	 * @return 
	 */
	public function getId(): ?int {
		return $this->id;
	}
	
	/**
	 * @param  $id 
	 * @return self
	 */
	public function setId(?int $id): self {
		$this->id = $id;
		return $this;
	}
}