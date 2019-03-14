<?php declare(strict_types=1);

namespace JasminWeb\Jasmin\Command;

/**
 * Class for add post-validation on specific commands
 *
 * Class InternalAddValidator
 * @package JasminWeb\Jasmin\Command
 */
abstract class InternalAddValidator extends AddValidator
{
    /**
     * {@inheritdoc}
     */
    final public function checkRequiredAttributes(array $data): bool
    {
        if (!parent::checkRequiredAttributes($data)) {
            return false;
        }

        $validator = $this->resolveValidator($data);
        if (!$validator) {
            $this->addResolveError($data);
            return false;
        }

        $validator->checkRequiredAttributes($data);

        if ($validator->hasErrors()) {
            $this->errors = $validator->getErrors();
        }

        return $validator->hasErrors();
    }

    /**
     * Find validator by data
     *
     * @param array $data
     *
     * @return AddValidator|null
     */
    abstract protected function resolveValidator(array $data): ?AddValidator;

    /**
     * Add specific error if validator isn't found
     *
     * @param array $data
     */
    abstract protected function addResolveError(array $data): void;
}