<?php

namespace App\Ui\Api\Controllers;

use App\Ui\Shared\Traits\GetUserEntityFromController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class ApiController extends AbstractController
{
    use GetUserEntityFromController;
}