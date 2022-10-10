<?php

namespace App\Comment;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

final class CreateComment implements CreateCommentInterface
{
    public function __construct(private Security $security, private CommentRepository $repo, private UserRepository $userRepo)
    {
    }

    public function __invoke(Comment $comment, Trick $trick): void
    {
        $username = $this->security->getUser()->getUserIdentifier();
        $user = $this->userRepo->get($username);
        $comment->setAuthor($user);
        $comment->setCreated(new \DateTime());
        $comment->setTrick($trick);
        $this->repo->add($comment, true);
    }
}
