<?php

namespace App\Serializers;

use Limenius\Liform\Liform;
use Limenius\Liform\Serializer\Normalizer\FormErrorNormalizer;
use Limenius\Liform\Serializer\Normalizer\InitialValuesNormalizer;
use ReflectionException;
use Symfony\Component\Form\FormInterface;
use ReflectionClass;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

class FormSerializer
{
    public function __construct
    (
        private readonly Liform $liform,
        private readonly InitialValuesNormalizer $liformNormalizer,
        private readonly FormErrorNormalizer $liformErrorNormalizer,
    ){}

    /**
     * @throws ExceptionInterface
     */
    public function serialize(FormInterface $form): array
    {
        $schema = $this->liform->transform($form);

        if ($this->isValueNormalizerApplicable($form)) {
            $values = $this->liformNormalizer->normalize($form);
            $csrfToken = ($form->getConfig()->getOption('csrf_protection') ? $this->getFormCsrfTokenId($form) : false);
        } else {
            $values = (object)[];
            $csrfToken = null;
        }

        return [
            'schema' => $schema,
            'values' => $values,
            'errors'  =>  $this->isErrorNormalizerApplicable($form) ? $this->liformErrorNormalizer->normalize($form) : (object)[],
            'csrf_token' => $csrfToken
        ];
    }

    private function getFormCsrfTokenId(FormInterface $form): array
    {
        $csrfToken =  $form->getConfig()->getOption('csrf_token_id') ?: ($form->getName() ?: get_class($form->getConfig()->getType()->getInnerType()));
        $csrfFieldName =  $form->getConfig()->getOption('csrf_field_name');
        $csrfTokenManager =  $form->getConfig()->getOption('csrf_token_manager');

        return [
            'name' => $csrfFieldName,
            'value' => $csrfTokenManager->getToken($csrfToken)->getValue(),
        ];
    }

    private function isErrorNormalizerApplicable(FormInterface $form): bool
    {
        return $form->isSubmitted() && !$form->isValid();
    }

    private function isValueNormalizerApplicable(FormInterface $form): bool
    {
        return (!$form->isSubmitted() || $form->isValid());
    }

    // TODO: This needs to probably needs to be changed, to validate the fields are as supposed.
    public function deserialize(string $typeJson, FormInterface $form): FormInterface
    {

    }

    private function oldSerialize(FormInterface $form) : string
    {
        // get fucked
        $formData = [];
        foreach ($form->all() as $child) {
            $fieldName = $child->getName();
            $fieldConfig = $child->getConfig();

            $fieldTypeClass = get_class($child->getConfig()->getType()->getInnerType());
            try
            {
                $fieldType = (new ReflectionClass($fieldTypeClass))->getShortName();
            } catch (ReflectionException $e)
            {
                $fieldType = 'Unknown type';
            }

            $formOptions = $fieldConfig->getOptions();
            // Form options to display ->
            $keysOfInterest = ['required', 'attr'];
            $filteredOptions = [];
            foreach ($formOptions as $key => $value) {
                if (in_array($key, $keysOfInterest)) {
                    $filteredOptions[$key] = $value;
                }
            }

            $fieldValue = $child->getData();

            $formData[$fieldName] = [
                'type' => $fieldType,
                'value' => $fieldValue,
                'options' => $filteredOptions,
            ];
        }

        return json_encode($formData  ?? "It was empty");
    }
}