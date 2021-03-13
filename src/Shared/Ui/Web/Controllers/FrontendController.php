<?php

namespace App\Shared\Ui\Web\Controllers;

use App\Auth\Application\QueryServices\CurrentUserFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class FrontendController extends AbstractController
{
    protected const PER_PAGE_DEFAULT = 10;

    /**
     * @required
     */
    public CurrentUserFetcherInterface $userFetcher;
}