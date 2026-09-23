<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\PropertyProcessor;

use Hostnet\Component\AccessorGenerator\Attribute\Enumerator;
use Hostnet\Component\AccessorGenerator\Attribute\Generate;

/**
 * Processes Generate and Enumerator attributes and determines which
 * accessor methods should be generated. Stores the result in a PropertyInformation object.
 */
class AccessorGenerationProcessor implements PropertyProcessorInterface
{
    #[\Override]
    public function apply(object $attribute, PropertyInformation $info): void
    {
        // Standalone Enumerator attribute.
        if ($attribute instanceof Enumerator) {
            $info->addEnumeratorToGenerate($attribute);
            $attribute->setProperty($info->getName());
            if (! $info->getType() && $attribute->getType()) {
                $info->setType($attribute->getType());
            }
            return;
        }

        // Only process Generate attributes from this point.
        if (!$attribute instanceof Generate) {
            return;
        }

        $info->setIsGenerator(true);

        if ($attribute->getEnumerators()) {
            $attribute->setDefaultVisibility(Generate::VISIBILITY_NONE);
            foreach ($attribute->getEnumerators() as $enumerator) {
                $info->addEnumeratorToGenerate($enumerator);
            }
        } else {
            $attribute->setDefaultVisibility(Generate::VISIBILITY_PUBLIC);
        }

        // By default no method is generated.
        //
        // Each processor can enforce a limitation on the generated methods.
        // If processor A lets a method be private, and processor B tells it to
        // be protected, it will end up private.

        $info->limitMaximumGetVisibility(
            Generate::getMostLimitedVisibility($attribute->getGet(), $attribute->getIs())
        );
        $info->limitMaximumSetVisibility(
            Generate::getMostLimitedVisibility($attribute->getSet())
        );
        $info->limitMaximumAddVisibility(
            Generate::getMostLimitedVisibility($attribute->getAdd(), $attribute->getSet())
        );
        $info->limitMaximumRemoveVisibility(
            Generate::getMostLimitedVisibility($attribute->getRemove(), $attribute->getSet())
        );

        null === $info->getType() && $attribute->getType() && $info->setType($attribute->getType());
        null !== $attribute->getType() && $info->setTypeHint($attribute->getType());
        null !== $attribute->getEncryptionAlias() && $info->setEncryptionAlias($attribute->getEncryptionAlias());

        // Enforce always
        $info->setGenerateStrict($attribute->isStrict());
        $info->setIsGenerator(true);
    }
}
