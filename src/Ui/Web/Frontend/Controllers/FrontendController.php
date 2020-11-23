<?php

namespace App\Ui\Web\Frontend\Controllers;

use App\Ui\Shared\Traits\GetUserEntityFromController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class FrontendController extends AbstractController
{
    protected const PER_PAGE_DEFAULT = 10;

    use GetUserEntityFromController;
}