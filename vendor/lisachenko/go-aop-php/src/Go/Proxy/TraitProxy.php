<?php
/**
 * Go! AOP framework
 *
 * @copyright Copyright 2012, Lisachenko Alexander <lisachenko.it@gmail.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Go\Proxy;

use Go\Core\AspectKernel;
use Go\Core\LazyAdvisorAccessor;
use ReflectionMethod as Method;
use ReflectionParameter as Parameter;
use Go\Aop\Advice;
use Go\Core\AspectContainer;
use TokenReflection\ReflectionMethod as ParsedMethod;
use TokenReflection\ReflectionParameter as ParsedParameter;

/**
 * AOP Factory that is used to generate trait proxy from joinpoints
 */
class TraitProxy extends ClassProxy
{

    /**
     * List of advices for traits
     *
     * @var array
     */
    protected static $traitAdvices = array();

    /**
     * Inject advices for given trait
     *
     * NB This method will be used as a callback during source code evaluation to inject joinpoints
     *
     * @param string $className Aop child proxy class
     * @param array|\Go\Aop\Advice[] $traitAdvices List of advices to inject into class
     *
     * @return void
     */
    public static function injectJoinPoints($className, array $traitAdvices = array())
    {
        self::$traitAdvices[$className] = $traitAdvices;
    }

    public static function getJoinPoint($traitName, $className, $joinPointType, $pointName)
    {
        /** @var LazyAdvisorAccessor $accessor */
        static $accessor = null;

        if (!self::$invocationClassMap) {
            self::setMappings();
            $accessor = AspectKernel::getInstance()->getContainer()->get('aspect.advisor.accessor');
        }

        $advices = self::$traitAdvices[$traitName][$joinPointType][$pointName];

        $filledAdvices = array();
        foreach ($advices as $advisorName) {
            $filledAdvices[] = $accessor->$advisorName;
        }

        $joinpoint = new self::$invocationClassMap[$joinPointType]($className, $pointName . '➩', $filledAdvices);

        return $joinpoint;
    }

    /**
     * Creates definition for trait method body
     *
     * @param Method|ParsedMethod $method Method reflection
     *
     * @return string new method body
     */
    protected function getJoinpointInvocationBody($method)
    {
        $isStatic = $method->isStatic();
        $class    = '\\' . __CLASS__;
        $scope    = $isStatic ? '\get_called_class()' : '$this';
        $prefix   = $isStatic ? AspectContainer::STATIC_METHOD_PREFIX : AspectContainer::METHOD_PREFIX;

        $args = join(', ', array_map(function ($param) {
            /** @var $param Parameter|ParsedParameter */
            $byReference = $param->isPassedByReference() ? '&' : '';

            return $byReference . '$' . $param->name;
        }, $method->getParameters()));

        $args = $scope . ($args ? ", array($args)" : '');

        return <<<BODY
static \$__joinPoint = null;
if (!\$__joinPoint) {
    \$__joinPoint = {$class}::getJoinPoint(__TRAIT__, __CLASS__, '{$prefix}', '{$method->name}');
}
return \$__joinPoint->__invoke($args);
BODY;
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {
        $classCode = sprintf("%s\ntrait %s\n{\n%s\n\n%s\n}",
            $this->class->getDocComment(),
            $this->name,
            $this->indent(
                'use ' . join(', ', array(-1 => $this->parentClassName) + $this->traits) .
                $this->getMethodAliasesCode()
            ),
            $this->indent(join("\n", $this->methodsCode))
        );

        return $classCode
            // Inject advices on call
            . PHP_EOL
            . '\\' . __CLASS__ . "::injectJoinPoints('"
                . $this->class->name . "',"
                . var_export($this->advices, true) . ");";
    }

    private function getMethodAliasesCode()
    {
        $aliasesLines = array();
        foreach (array_keys($this->methodsCode) as $methodName) {
            $aliasesLines[] = "{$this->parentClassName}::{$methodName} as protected {$methodName}➩;";
        }

        return "{\n " . $this->indent(join("\n", $aliasesLines)) . "\n}";
    }
}
