<?php
/**
 * @author Kirill chEbba Chebunin
 * @author Vasil coylOne Kulakov <kulakov@vasiliy.pro>
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */
namespace EventBand\Tests\Routing;

use EventBand\Event;
use EventBand\Routing\EventPatternRouter;
use EventBand\Routing\EventRoutingException;
use PHPUnit\Framework\TestCase;

class EventPatternRouterTest extends TestCase
{
    /**
     * @test getPlaceholders parsing
     * @dataProvider placeholderParsingData
     *
     * @param string $pattern
     * @param array  $placeholders
     * @param string $message
     */
    public function placeholderParsing($pattern, array $placeholders, $message)
    {
        $router = new EventPatternRouter($pattern);
        $this->assertEquals($placeholders, $router->getPlaceholders(), $message);
    }

    public function placeholderParsingData()
    {
        return array(
            array('{name}', array('{name}' => 'name'), 'Simple placeholder'),
            array('{name123}', array('{name123}' => 'name123'), 'Placeholder with digits'),
            array('{name[123]}', array('{name[123]}' => 'name[123]'), 'Array placeholder'),
            array('{name[foo_bar].baz}', array('{name[foo_bar].baz}' => 'name[foo_bar].baz'), 'Array property placeholder'),
            array('foo', array(), 'No placeholders'),
            array('{}', array(), 'Empty placeholder'),
            array('{foo}.{bar}', array('{foo}' => 'foo', '{bar}' => 'bar'), '2 placeholders'),
            array('{foo}.{bar}.{foo}', array('{foo}' => 'foo', '{bar}' => 'bar'), 'Equal placeholders'),
        );
    }

    /**
     * @test routeEvent replaces placeholders
     */
    public function routerReplacement()
    {
        $router = new EventPatternRouter('foo.{name}.bar.{name}');
        $event = $this->createMock('EventBand\Event');
        $event
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue('nameValue'))
        ;

        $this->assertEquals('foo.nameValue.bar.nameValue', $router->routeEvent($event));
    }

    /**
     * @test routeEvent throws exception on non-scalar replacements
     *
     * @expectedException \EventBand\Routing\EventRoutingException
     * @expectedExceptionMessage is not a scalar
     */
    public function routeReplaceNonScalars()
    {
        $router = new EventPatternRouter('{time}');
        $event = new EventMock();

        $router->routeEvent($event);
    }

    /**
     * @test routeEvent throws exception if property is not found
     *
     * @expectedException \EventBand\Routing\EventRoutingException
     * @expectedExceptionMessage Can not replace
     */
    public function routeReplaceNotExisting()
    {
        $router = new EventPatternRouter('{foo}');
        $event = $this->createMock(Event::class);

        $router->routeEvent($event);
    }
}
