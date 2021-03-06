<?php
/**
 * Matryoshka
 *
 * @link        https://github.com/matryoshka-model/matryoshka
 * @copyright   Copyright (c) 2014, Ripa Club
 * @license     http://opensource.org/licenses/BSD-2-Clause Simplified BSD License
 */
namespace MatryoshkaTest\Model\Service;

use Matryoshka\Model\Service\ModelAbstractServiceFactory;
use Zend\ServiceManager;
use Zend\Mvc\Service\ServiceManagerConfig;
use Matryoshka\Model\ResultSet\ArrayObjectResultSet as ResultSet;
use MatryoshkaTest\Model\Service\TestAsset\DomainObject;
use MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria;

/**
 * Class ModelAbstractServiceFactoryTest
 */
class ModelAbstractServiceFactoryTest extends \PHPUnit_Framework_TestCase
{


    /**
     * @var \Zend\ServiceManager\ServiceManager
     */
    protected $serviceManager;

    /**
     * @return void
     */
    public function setUp()
    {
        $dataGateway = new \MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway;
        $resultSet   = new ResultSet;
        $objectPrototype = new DomainObject();
        $paginatorCriteria = new PaginatorCriteria();



        $config = [
            'model' => [
                'MyModel\A' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset'   => 'Matryoshka\Model\ResultSet\ResultSet',
                ],
                'MyModel\B' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset'   => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object'      => 'ArrayObject',
                    'hydrator'    => 'Zend\Stdlib\Hydrator\ArraySerializable',
                    'type'        => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',
                ],
                'MyModel\InvalidTypeModel' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset'   => 'Matryoshka\Model\ResultSet\ResultSet',
                    'type'        => '\stdClass',
                ],
                'MyModel\Full' => [
                    'datagateway' => 'MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway',
                    'resultset'   => 'Matryoshka\Model\ResultSet\HydratingResultSet',
                    'object'      => 'DomainObject',
                    'hydrator'    => 'Zend\Stdlib\Hydrator\ObjectProperty',
                    'input_filter'=> 'Zend\InputFilter\InputFilter',
                    'paginator_criteria'=> 'MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria',
                    'type'        => 'MatryoshkaTest\Model\Service\TestAsset\MyModel',

                ],
            ],
        ];

        $sm = $this->serviceManager = new ServiceManager\ServiceManager(
            new ServiceManagerConfig([
                'abstract_factories' => [
                    'Matryoshka\Model\Service\ModelAbstractServiceFactory',
                ]
            ])
        );

        $sm->setService('Config', $config);
        $sm->setService('MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway', new \MatryoshkaTest\Model\Service\TestAsset\FakeDataGateway);
        $sm->setService('Matryoshka\Model\ResultSet\ResultSet', new ResultSet);
        $sm->setService('Matryoshka\Model\ResultSet\HydratingResultSet', new \Matryoshka\Model\ResultSet\HydratingResultSet);
        $sm->setService('Zend\Stdlib\Hydrator\ArraySerializable', new \Zend\Stdlib\Hydrator\ArraySerializable);
        $sm->setService('Zend\Stdlib\Hydrator\ObjectProperty', new \Zend\Stdlib\Hydrator\ObjectProperty);
        $sm->setService('Zend\InputFilter\InputFilter', new \Zend\InputFilter\InputFilter);
        $sm->setService('MatryoshkaTest\Model\Service\TestAsset\PaginatorCriteria', $paginatorCriteria);
        $sm->setService('ArrayObject', new \ArrayObject);
        $sm->setService('DomainObject', $objectPrototype);
    }


    /**
     * @return void
     */
    public function testCanCreateService()
    {
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = $this->serviceManager;

        $this->assertFalse($factory->canCreateServiceWithName($serviceLocator, 'mymodelnonexistingmodel', 'MyModel\NonExistingModel'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'mymodela', 'MyModel\A'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'mymodela', 'MyModel\B'));
        $this->assertTrue($factory->canCreateServiceWithName($serviceLocator, 'mymodela', 'MyModel\Full'));

        //test without config
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = new ServiceManager\ServiceManager(
            new ServiceManagerConfig()
        );

        $this->assertFalse($factory->canCreateServiceWithName($serviceLocator, 'mymodelnonexistingmodel', 'MyModel\NonExistingModel'));

        //test with empty config
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator->setService('Config', []);

        $this->assertFalse($factory->canCreateServiceWithName($serviceLocator, 'mymodelnonexistingmodel', 'MyModel\NonExistingModel'));
    }

    /**
     * @depends testCanCreateService
     * @return void
     */
    public function testCreateService()
    {
        $serviceLocator = $this->serviceManager;

        $modelA = $serviceLocator->get('MyModel\A');
        $this->assertInstanceOf('\Matryoshka\Model\Model', $modelA);


        $modelB = $serviceLocator->get('MyModel\B');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\MyModel', $modelB);

        $modelFull = $serviceLocator->get('MyModel\Full');
        $this->assertInstanceOf('\MatryoshkaTest\Model\Service\TestAsset\MyModel', $modelFull);
    }

    /**
     * @depends testCreateService
     * @return void
     */
    public function testCreateServiceShouldThrowExceptionOnInvalidType()
    {
        $factory = new ModelAbstractServiceFactory();
        $serviceLocator = $this->serviceManager;
        $this->setExpectedException('\Matryoshka\Model\Exception\UnexpectedValueException');

        $factory->createServiceWithName($serviceLocator, 'mymodelinvalidtypemodel', 'MyModel\InvalidTypeModel');
    }
}