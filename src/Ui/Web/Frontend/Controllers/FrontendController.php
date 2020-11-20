<?php

namespace App\Ui\Web\Frontend\Controllers;

use App\Ui\Shared\Traits\GetUserEntityFromController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class FrontendController extends AbstractController
{
    use GetUserEntityFromController;
}