<?php

declare(strict_types=1);


namespace App\Common\Exception;


use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationInterface;

final class BadRequestException extends BadRequestHttpException
{
    /**
     * @param array $errors
     *
     * @return BadRequestException
     */
    public static function createFromErrors(array $errors)
    {
        $message = [];

        foreach ($errors as $error) {
            /* @var ConstraintViolationInterface $error */
            $message[$error->getPropertyPath()] = $error->getMessage();
        }

        return new static(json_encode($message));
    }

    /**
     * @param FormInterface $form
     *
     * @return BadRequestException
     */
    public static function createFromForm(FormInterface $form)
    {
        $message = [];

        foreach (iterator_to_array($form->getErrors(true, true)) as $error) {
            /* @var FormError $error */
            $ancestry = self::getAncestry($form = $error->getOrigin());
            $ancestry[] = $form->getName();

            $path = array_shift($ancestry);

            foreach ($ancestry as $parent) {
                $path .= "[{$parent}]";
            }

            $message[$path] = $error->getMessage();
        }

        return new static(json_encode($message));
    }

    /**
     * @param FormInterface $form
     * @param array                                 $ancestry
     *
     * @return array
     */
    private static function getAncestry(FormInterface $form, array $ancestry = [])
    {
        $parent = $form->getParent();

        if (null !== $parent) {
            $formName = $parent->getName();

            if ('' !== $formName) {
                $ancestry = self::getAncestry($parent, $ancestry);
                $ancestry[] = $formName;
            }
        }

        return $ancestry;
    }
}
