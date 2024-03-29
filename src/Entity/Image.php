<?php

namespace App\Entity;

use App\FileUploader\UploadableInterface;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image implements UploadableInterface
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	private $id;

	#[ORM\Column(type: 'string', length: 255)]
	private $name;

	#[ORM\Column(type: 'string', length: 255)]
	private $filename;

	#[ORM\ManyToOne(targetEntity: Trick::class, inversedBy: 'images')]
	#[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
	private $trick;

	private $image;

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	public function getFilename(): ?string
	{
		return $this->filename;
	}

	public function setFilename(string $filename): self
	{
		$this->filename = $filename;

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

	public function getImage(): ?UploadedFile
	{
		return $this->image;
	}

	public function setImage(?UploadedFile $image): self
	{
		$this->image = $image;

		return $this;
	}
}
