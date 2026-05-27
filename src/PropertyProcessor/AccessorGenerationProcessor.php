<?php
/**
 * @copyright 2014-2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\PropertyProcessor;

use Hostnet\Component\AccessorGenerator\Attribute\Enumerator;
use Hostnet\Component\AccessorGenerator\Attribute\Generate;

/**
 * Processes Generate and Enumerator annotations/attributes and determines which
 * accessor methods should be generated. Stores the result in a PropertyInformation object.
 *
 * This processor is path-agnostic: it receives already-instantiated objects regardless of
 * whether they originated from a docblock annotation (parsed by Doctrine's DocParser) or a
 * native PHP 8 attribute (evaluated by AttributeInstantiator). Both paths produce the same
 * Generate/Enumerator instances, so no branching on the source is needed here.
 */
class AccessorGenerationProcessor implements PropertyProcessorInterface
{
    /**
     * @see PropertyProcessorInterface::apply()
     *
     * @param object              $annotation
     * @param PropertyInformation $info
     */
    public function apply($annotation, PropertyInformation $info): void
    {
        // Standalone Enumerator annotation.
        if ($annotation instanceof Enumerator) {
            $info->addEnumeratorToGenerate($annotation);
            $annotation->setProperty($info->getName());
            if (! $info->getType() && $annotation->getType()) {
                $info->setType($annotation->getType());
            }
            return;
        }

        // Only process Generate annotations from this point.
        if (!$annotation instanceof Generate) {
            return;
        }

        $info->setIsGenerator(true);

        if ($annotation->getEnumerators()) {
            $annotation->setDefaultVisibility(Generate::VISIBILITY_NONE);
            foreach ($annotation->getEnumerators() as $enumerator) {
                $info->addEnumeratorToGenerate($enumerator);
            }
        } else {
            $annotation->setDefaultVisibility(Generate::VISIBILITY_PUBLIC);
        }

        // By default no method is generated.
        //
        // Each processor can enforce a limitation on the generated methods.
        // If processor A lets a method be private, and processor B tells it to
        // be protected, it will end up private.

        $info->limitMaximumGetVisibility(
            Generate::getMostLimitedVisibility($annotation->getGet(), $annotation->getIs())
        );
        $info->limitMaximumSetVisibility(
            Generate::getMostLimitedVisibility($annotation->getSet())
        );
        $info->limitMaximumAddVisibility(
            Generate::getMostLimitedVisibility($annotation->getAdd(), $annotation->getSet())
        );
        $info->limitMaximumRemoveVisibility(
            Generate::getMostLimitedVisibility($annotation->getRemove(), $annotation->getSet())
        );

        null === $info->getType() && $annotation->getType() && $info->setType($annotation->getType());
        null !== $annotation->getType() && $info->setTypeHint($annotation->getType());
        null !== $annotation->getEncryptionAlias() && $info->setEncryptionAlias($annotation->getEncryptionAlias());

        // Enforce always
        $info->setGenerateStrict($annotation->isStrict());
        $info->setIsGenerator(true);
    }

    public function getProcessableNamespace(): string
    {
        return 'Hostnet\Component\AccessorGenerator\Annotation';
    }
}
