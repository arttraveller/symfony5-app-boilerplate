<?php

namespace App\Ui\Api\Controllers;

use App\Exceptions\ApiValidationException;
use App\Ui\Shared\Traits\GetUserEntityFromController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ApiController extends AbstractController
{
    protected const PER_PAGE_DEFAULT = 20;

    use GetUserEntityFromController;

    protected SerializerInterface $serializer;
    protected ValidatorInterface $validator;


    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }


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