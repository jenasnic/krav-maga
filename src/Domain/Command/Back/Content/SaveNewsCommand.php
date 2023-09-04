<?php

namespace App\Domain\Command\Back\Content;

use App\Entity\Content\News;

class SaveNewsCommand
{
    public function __construct(public News $news)
    {
    }
}
