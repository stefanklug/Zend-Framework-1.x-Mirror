<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Amf
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: TypeLoaderTest.php 23775 2011-03-01 17:25:24Z ralph $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Amf_TypeloaderTest::main');
}

require_once 'Zend/Amf/TypeMapper.php';

/**
 * @category   Zend
 * @package    Zend_Amf
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Amf
 */
class Zend_Amf_TypeMapperTest extends PHPUnit_Framework_TestCase
{
/**
     * Zend_Amf_TypeMapper object
     * @var Zend_Amf_TypeMapper
     */
    protected $_typeLoader;

    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Zend_Amf_ResponseTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }
    
	/**
     * Setup environment
     */
    public function setUp()
    {
        date_default_timezone_set("America/Chicago");
        Zend_Locale::setDefault('en');
        $this->_typeLoader = new Zend_Amf_TypeMapper();
    }

    /**
     * Teardown environment
     */
    public function tearDown()
    {
        unset($this->_typeLoader);
    }

    /**
     * test that we can get the server name from the client name for deserialization.
     *
     */
    public function testGetMappedClassNameForClient()
    {
        $class = $this->_typeLoader->getLocalClassName('flex.messaging.messages.RemotingMessage');
        $this->assertEquals('Zend_Amf_Value_Messaging_RemotingMessage', $class);
    }

    /**
     * Test that we can get the return name from the server name for serialization
     *
     */
    public function testGetMappedClassNameForServer()
    {
        $class = $this->_typeLoader->getRemoteClassName('Zend_Amf_Value_Messaging_RemotingMessage');
        $this->assertEquals('flex.messaging.messages.RemotingMessage', $class);
    }

    /**
     * Test that adding our own mappping will result in it being added to the classMap
     *
     */
    public function testSetMappingClass()
    {
        $this->_typeLoader->setClassMap('com.example.vo.Contact','Contact');
        $class = $this->_typeLoader->getLocalClassName('com.example.vo.Contact');
        $this->assertEquals('Contact', $class);
    }

    public function testUnknownClassMap() {
        $class = $this->_typeLoader->getLocalClassName('com.example.vo.Bogus');
        $this->assertEquals('stdClass', $class);
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Amf_TypeloaderTest::main') {
    Zend_Amf_ResponseTest::main();
}

