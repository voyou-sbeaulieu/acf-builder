<?php

namespace StoutLogic\AcfBuilder\Tests;

use StoutLogic\AcfBuilder\FieldBuilder;
use StoutLogic\AcfBuilder\FieldsBuilder;
use StoutLogic\AcfBuilder\GroupBuilder;

class GroupBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $testFields;
    public function setup()
    {
        $this->testFields['test1'] = new FieldBuilder('test1', 'text');
        $this->testFields['test2'] = new FieldBuilder('test2', 'text');
        $this->testFields['test3'] = new FieldBuilder('test3', 'text');
        $this->testFields['test4'] = new FieldBuilder('test4', 'text');
    }

    public function testGroupBuilderCanAddFields()
    {
        $builder = new GroupBuilder('background');

        $builder->addColorPicker('color');

        $expectedConfig = [
            'name' => 'background',
            'type' => 'group',
            'sub_fields' => [
                [
                    'type' => 'color_picker',
                    'name' => 'color',
                ],
            ],

        ];

        $this->assertArraySubset($expectedConfig, $builder->build());
    }

    public function testGroupBuilderAddFieldsGroupFields()
    {
        $size = new FieldsBuilder('size');
        $size
            ->addNumber('width')
            ->addNumber('height');

        $builder = new GroupBuilder('background');

        $builder
            ->addColorPicker('color')
            ->addFields($size);

        $expectedConfig = [
            'name' => 'background',
            'type' => 'group',
            'sub_fields' => [
                [
                    'type' => 'color_picker',
                    'name' => 'color',
                ],
                [
                    'type' => 'number',
                    'name' => 'width',
                ],
                [
                    'type' => 'number',
                    'name' => 'height',
                ],
            ],

        ];

        $this->assertArraySubset($expectedConfig, $builder->build());
    }

    public function testGroupFieldGetFields()
    {
        $builder = new GroupBuilder('test');

        $builder->addFields([
            $this->testFields['test1'],
            $this->testFields['test2'],
            $this->testFields['test3'],
        ]);

        $this->assertCount(3, $builder->getFields());
    }

    public function testGroupFieldGetField()
    {
        $builder = new GroupBuilder('test');

        $builder->addFields([
            $this->testFields['test1'],
            $this->testFields['test2'],
            $this->testFields['test3'],
        ]);

        $this->assertSame($this->testFields['test3'], $builder->getField('test3'));
    }

    public function testGroupFieldRemoveField()
    {
        $builder = new GroupBuilder('test');

        $builder->addFields([
            $this->testFields['test1'],
            $this->testFields['test2'],
            $this->testFields['test3'],
        ]);

        $builder->removeField('test2');

        $this->assertSame([
            $this->testFields['test1'],
            $this->testFields['test3'],
        ], $builder->getFields());
    }

    public function testGroupFieldModifyField()
    {
        $builder = new GroupBuilder('test');

        $builder->addFields([
            $this->testFields['test1'],
        ]);

        $builder->modifyField('test1', ['label' => 'new label']);

        $this->assertEquals([
            'key' => 'field_test1',
            'name' => 'test1',
            'label' => 'new label',
            'type' => 'text',
        ], $builder->getField('test1')->build());
    }
}
