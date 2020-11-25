<?php

namespace App\Ui\Web\Frontend\Controllers;

use App\Core\Fetchers\Interfaces\CurrentUserFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class FrontendController extends AbstractController
{
    protected const PER_PAGE_DEFAULT = 10;

    /**
     * @required
     */
    public CurrentUserFetcherInterface $userFetcher;
}