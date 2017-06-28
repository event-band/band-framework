<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Tests\Utils;

use EventBand\Utils\ClassUtils;
use PHPUnit_Framework_TestCase as TestCase;

class ClassUtilsTest extends TestCase
{
    /**
     * @test class to name conversion
     * @dataProvider classNames
     *
     * @param string $className
     * @param string $convertedName
     */
    public function classToNameConversion($className, $convertedName)
    {
        $this->assertEquals($convertedName, ClassUtils::classToName($className, '/', '-'));
    }

    /**
     * @test name to class conversion
     * @dataProvider classNames
     *
     * @param string $className
     * @param string $convertedName
     */
    public function nameToClassConversion($className, $convertedName)
    {
        $this->assertEquals($className, ClassUtils::nameToClass($convertedName, '/', '-'));
    }

    /**
     * @return array An array of [[$className, $convertedName], ...]
     */
    public function classNames()
    {
        return [
            ['Foo\Ns\Class', 'foo/ns/class'],
            ['FooBar\Ns\Class', 'foo-bar/ns/class'],
            ['FooBar\Ns\ClassName', 'foo-bar/ns/class-name'],
            ['FooBar\Ns\Class35Name', 'foo-bar/ns/class35-name'],
            ['FooBar\Ns\STDClass', 'foo-bar/ns/s-t-d-class']
        ];
    }
}
