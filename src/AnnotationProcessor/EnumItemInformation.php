<?php
/**
 * @copyright 2018 Hostnet B.V.
 */
declare(strict_types=1);

namespace Hostnet\Component\AccessorGenerator\AnnotationProcessor;

use Doctrine\Common\Inflector\Inflector;

class EnumItemInformation
{
    private const TYPE_MAP = ['S_' => 'string', 'I_' => 'int', 'F_' => 'float', 'A_' => 'array', 'B_' => 'bool'];

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $type_hint;

    /**
     * @var string
     */
    private $doc_block;

    /**
     * @var string
     */
    private $method_name;

    /**
     * @var string
     */
    private $enum_class;

    /**
     * @var string
     */
    private $const_name;

    public function __construct(\ReflectionClassConstant $constant)
    {
        $type = substr($constant->name, 0, 2);
        if (false === array_key_exists($type, self::TYPE_MAP)) {
            throw new \InvalidArgumentException(sprintf(
                'The name of the constant "%s" is not prefixed with a valid type string (%s)',
                $constant->name,
                implode(', ', array_keys(self::TYPE_MAP))
            ));
        }

        $this->name        = substr($constant->name, 2);
        $this->enum_class  = $constant->getDeclaringClass()->getName();
        $this->const_name  = $constant->name;
        $this->doc_block   = trim(trim(trim($constant->getDocComment()), '/**/'));
        $this->type_hint   = self::TYPE_MAP[$type];
        $this->method_name = Inflector::classify(strtolower($this->name));
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTypeHint(): string
    {
        return $this->type_hint;
    }

    public function getDocBlock(): string
    {
        return $this->doc_block;
    }

    public function getMethodName(): string
    {
        return $this->method_name;
    }

    public function getEnumClass(): string
    {
        return $this->enum_class;
    }

    public function getConstName(): string
    {
        return $this->const_name;
    }
}
