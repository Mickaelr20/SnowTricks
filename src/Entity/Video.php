<?php

namespace App\Entity;

use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	private ?int $id = null;

	#[ORM\Column(type: 'string', length: 255)]
	private string $title;

	#[ORM\Column(type: 'string', length: 255)]
	private string $link;

	#[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'videos')]
	#[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
	private ?Trick $trick = null;

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

	public function getLink(): ?string
	{
		return $this->link;
	}

	public function setLink(string $link): self
	{
		$this->link = $link;

		return $this;
	}

	public function getTrick(): ?Trick
	{
		return $this->trick;
	}

	public function setTrick(?Trick $trick): self
	{
		$this->trick = $trick;

		return $this;
	}
}
