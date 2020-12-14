<?php

namespace App\Domain\Commands\Posts;

use Symfony\Component\Validator\Constraints as Assert;

class CreatePostCommand
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(max=255)
     */
    public string $title = '';

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=10, max=100000)
     */
    public string $text = '';
}
