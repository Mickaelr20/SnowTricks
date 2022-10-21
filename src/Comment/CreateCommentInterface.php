<?php

namespace App\Comment;

use App\Entity\Comment;
use App\Entity\Trick;

interface CreateCommentInterface
{
	public function __invoke(Comment $comment, Trick $trick): void;
}
