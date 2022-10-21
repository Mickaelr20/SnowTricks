<?php

namespace App\Entity;

use App\FileUploader\UploadableInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface, UploadableInterface
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column(type: 'integer')]
	private ?int $id = null;

	#[ORM\Column(type: 'string')]
	private string $username;

	#[ORM\Column(type: 'string')]
	private string $email;

	#[ORM\Column(type: 'string')]
	private string $password;

	#[ORM\Column(type: 'string', length: 255, nullable: true)]
	private string $profilePictureFilename;

	/**
	 * @var Collection<int, Comment>
	 */
	#[ORM\OneToMany(mappedBy: 'author', targetEntity: Comment::class, orphanRemoval: true)]
	private Collection $comments;

	#[ORM\Column(type: 'string', length: 255, nullable: true)]
	private ?string $resetPasswordToken = null;

	#[ORM\Column(type: 'datetime', nullable: true)]
	private ?\DateTime $resetPasswordCreated = null;

	#[ORM\Column(type: 'datetime', nullable: true)]
	private ?\DateTime $resetPasswordExpire = null;

	public function __construct()
	{
		$this->comments = new ArrayCollection();
	}

	public function getId(): ?int
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

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string) $this->username;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		return ['ROLE_USER'];
	}

	/**
	 * @see PasswordAuthenticatedUserInterface
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	public function getProfilePictureFilename(): ?string
	{
		return $this->profilePictureFilename ?? null;
	}

	public function setProfilePictureFilename(?string $profilePictureFilename): self
	{
		$this->profilePictureFilename = $profilePictureFilename;

		return $this;
	}

	public function setFilename(?string $profilePictureFilename): self
	{
		$this->profilePictureFilename = $profilePictureFilename;

		return $this;
	}

	/**
	 * @return Collection<int, Comment>
	 */
	public function getComments(): Collection
	{
		return $this->comments;
	}

	public function addComment(Comment $comment): self
	{
		if (!$this->comments->contains($comment)) {
			$this->comments[] = $comment;
			$comment->setAuthor($this);
		}

		return $this;
	}

	public function removeComment(Comment $comment): self
	{
		if ($this->comments->removeElement($comment)) {
			// set the owning side to null (unless already changed)
			if ($comment->getAuthor() === $this) {
				$comment->setAuthor(null);
			}
		}

		return $this;
	}

	public function getResetPasswordToken(): ?string
	{
		return $this->resetPasswordToken;
	}

	public function setResetPasswordToken(?string $resetPasswordToken): self
	{
		$this->resetPasswordToken = $resetPasswordToken;

		return $this;
	}

	public function getResetPasswordCreated(): ?\DateTimeInterface
	{
		return $this->resetPasswordCreated;
	}

	public function setResetPasswordCreated(?\DateTimeInterface $resetPasswordCreated): self
	{
		$this->resetPasswordCreated = $resetPasswordCreated;

		return $this;
	}

	public function getResetPasswordExpire(): ?\DateTimeInterface
	{
		return $this->resetPasswordExpire;
	}

	public function setResetPasswordExpire(?\DateTimeInterface $resetPasswordExpire): self
	{
		$this->resetPasswordExpire = $resetPasswordExpire;

		return $this;
	}
}
