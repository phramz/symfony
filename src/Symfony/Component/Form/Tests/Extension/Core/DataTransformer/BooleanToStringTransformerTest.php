<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Form\Tests\Extension\Core\DataTransformer;

use Symfony\Component\Form\Extension\Core\DataTransformer\BooleanToStringTransformer;

class BooleanToStringTransformerTest extends \PHPUnit_Framework_TestCase
{
    const TRUE_VALUE = '1';

    /**
     * @var BooleanToStringTransformer
     */
    protected $transformer;

    protected function setUp()
    {
        $this->transformer = new BooleanToStringTransformer(self::TRUE_VALUE);
    }

    protected function tearDown()
    {
        $this->transformer = null;
    }

    public function testTransform()
    {
        $this->assertEquals(self::TRUE_VALUE, $this->transformer->transform(true));
        $this->assertNull($this->transformer->transform(false));
    }

    // https://github.com/symfony/symfony/issues/8989
    public function testTransformAcceptsNull()
    {
        $this->assertNull($this->transformer->transform(null));
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testTransformFailsIfString()
    {
        $this->transformer->transform('1');
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseTransformFailsIfInteger()
    {
        $this->transformer->reverseTransform(1);
    }

    /**
     * @expectedException \Symfony\Component\Form\Exception\TransformationFailedException
     */
    public function testReverseTransformFailsIfUnexspectedValue()
    {
        $this->transformer->reverseTransform('foobar');
    }

    /**
     * @dataProvider reverseTransformProvider
     */
    public function testReverseTransform($value, $result)
    {
        $this->assertEquals($result, $this->transformer->reverseTransform($value));
    }

    public function reverseTransformProvider()
    {
        return array(
            array(self::TRUE_VALUE, true),
            array('1', true),
            array(null, false),
            array(true, true),
            array(false, false),
            array('', false),
            array('0', false),
            array('false', false),
            array('true', true),
        );
    }
}
