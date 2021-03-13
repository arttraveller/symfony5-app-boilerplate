<?php

namespace App\Shared\Ui\Api;

use App\Auth\Application\QueryServices\CurrentUserFetcherInterface;
use App\Shared\Exceptions\ApiValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ApiController extends AbstractController
{
    protected const PER_PAGE_DEFAULT = 20;

    /**
     * @required
     */
    public SerializerInterface $serializer;
    /**
     * @required
     */
    public CurrentUserFetcherInterface $userFetcher;
    /**
     * @required
     */
    public ValidatorInterface $validator;


    protected function createCommand(Request $request, $commandClass): object
    {
        $command = null;
        try {
            $command = $this->serializer->deserialize($request->getContent(), $commandClass, 'json');
        } catch (NotEncodableValueException $exc) {
            throw new ApiValidationException('Invalid JSON');
        }

        return $command;
    }

    protected function validateCommand($command): void
    {
        $validationErrors = $this->validator->validate($command);
        if ($validationErrors->count() > 0) {
            $errors = [];
            foreach ($validationErrors as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
            // Put validation errors into exception object
            throw new ApiValidationException('', 0, null, $errors);
        }
    }
}