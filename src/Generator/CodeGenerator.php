<?php

namespace Hostnet\Component\AccessorGenerator\Generator;

use Doctrine\Common\Annotations\DocParser;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\DoctrineAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\GenerateAnnotationProcessor;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformation;
use Hostnet\Component\AccessorGenerator\AnnotationProcessor\PropertyInformationInterface;
use Hostnet\Component\AccessorGenerator\Reflection\Exception\ClassDefinitionNotFoundException;
use Hostnet\Component\AccessorGenerator\Reflection\ReflectionClass;
use Hostnet\Component\AccessorGenerator\Twig\CodeGenerationExtension;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Generate Trait files with accessor methods.
 * Put them in a Generated folder and namespace
 * relative to the file they are created for.
 *
 * @author Hidde Boomsma <hboomsma@hostnet.nl>
 */
class CodeGenerator implements CodeGeneratorInterface
{
    private $namespace   = 'Generated';
    private $name_suffix = 'MethodsTrait';

    /**
     * Initialize Twig and templates
     */
    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/../Resources/templates');
        $twig   = new \Twig_Environment($loader);
        $twig->clearTemplateCache();
        $twig->addExtension(new CodeGenerationExtension());

        $this->get    = $twig->loadTemplate('get.php.twig');
        $this->set    = $twig->loadTemplate('set.php.twig');
        $this->add    = $twig->loadTemplate('add.php.twig');
        $this->remove = $twig->loadTemplate('remove.php.twig');
        $this->trait  = $twig->loadTemplate('trait.php.twig');
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface::writeTraitForClass()
     * @param ReflectionClass $class
     * @return boolean
     */
    public function writeTraitForClass(ReflectionClass $class)
    {
        $data = $this->generateTraitForClass($class);
        $fs   = new Filesystem();

        if ($data) {
            $path     = dirname($class->getFilename()) . DIRECTORY_SEPARATOR . $this->namespace;
            $filename = $path . DIRECTORY_SEPARATOR . $class->getName() . $this->name_suffix . '.php';

            $fs->mkdir($path);
            $fs->dumpFile($filename, $data);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface::generateTraitForClass()
     * @param ReflectionClass $class
     * @return string
     */
    public function generateTraitForClass(ReflectionClass $class)
    {
        $code = '';

        try {
            $properties = $class->getProperties();
        } catch (ClassDefinitionNotFoundException $e) {
            $properties = [];
        }

        foreach ($properties as $property) {
            $parser = new DocParser();
            $info   = new PropertyInformation($property, $parser);
            $info->registerAnnotationProcessor(new GenerateAnnotationProcessor());
            $info->registerAnnotationProcessor(new DoctrineAnnotationProcessor());
            $info->processAnnotations();
            $code .= $this->generateAccessors($info);
        }
        if ($code) {
            $code = $this->trait->render([
                'namespace' => $class->getNamespace() . '\\' . $this->namespace,
                'name'      => $class->getName() . $this->name_suffix,
                'uses'      => $class->getUseStatements(),
                'methods'   => rtrim($code),
                'username'  => get_current_user(),
                'hostname'  => gethostname()
            ]);
        }

        return $code;
    }

    /**
     * @see \Hostnet\Component\AccessorGenerator\Generator\CodeGeneratorInterface::generateAccessors()
     * @param PropertyInformationInterface $info
     * @return string
     */
    public function generateAccessors(PropertyInformationInterface $info)
    {
        $code = '';

        if ($info->willGenerateGet()) {
            $code .= $this->get->render(['property' => $info]) . PHP_EOL;
        }

        if ($info->isCollection()) {
            if ($info->willGenerateAdd()) {
                $code .= $this->add->render(['property' => $info]). PHP_EOL;
            }
            if ($info->willGenerateRemove()) {
                $code .= $this->remove->render(['property' => $info]). PHP_EOL;
            }
        } else {
            if ($info->willGenerateSet()) {
                $code .= $this->set->render(['property' => $info]). PHP_EOL;
            }
        }

        return $code;
    }
}
