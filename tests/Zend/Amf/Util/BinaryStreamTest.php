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
 * @version    $Id: BinaryStreamTest.php 23775 2011-03-01 17:25:24Z ralph $
 */

if (!defined('PHPUnit_MAIN_METHOD')) {
    define('PHPUnit_MAIN_METHOD', 'Zend_Amf_Util_BinaryStreamTest::main');
}

require_once 'Zend/Amf/Util/BinaryStream.php';

/**
 * Test case for Zend_Amf_Util_BinaryStream
 *
 * @category   Zend
 * @package    Zend_Amf
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Amf
 */
class Zend_Amf_Util_BinaryStreamTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @return void
     */
    public static function main()
    {
        $suite  = new PHPUnit_Framework_TestSuite("Zend_Amf_Util_BinaryStreamTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * @expectedException Zend_Amf_Exception
     */
    public function testConstructorShouldThrowExceptionForInvalidStream()
    {
        $test = new Zend_Amf_Util_BinaryStream(array('foo', 'bar'));
    }
    
    public function testDefaultIsBigEndian()
    {
        $stream = new Zend_Amf_Util_BinaryStream('');
        $this->assertTrue($stream->getBigEndian());
        $stream->setBigEndian(false);
        $this->assertFalse($stream->getBigEndian());
    }

    /**
     * @expectedException Zend_Amf_Exception
     */
    public function testReadBytesShouldRaiseExceptionForBufferUnderrun()
    {
        $string = 'this is a short stream';
        $stream = new Zend_Amf_Util_BinaryStream($string);
        $length = strlen($string);
        $test   = $stream->readBytes(10 * $length);
    }

    public function testReadBytesShouldReturnSubsetOfStringFromCurrentNeedle()
    {
        $string = 'this is a short stream';
        $stream = new Zend_Amf_Util_BinaryStream($string);
        $test   = $stream->readBytes(4);
        $this->assertEquals('this', $test);
        $test   = $stream->readBytes(5);
        $this->assertEquals(' is a', $test);
    }

    public function testBinaryStreamsShouldAllowWritingUtf8()
    {
        $string = str_repeat('赵勇', 1000);
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->writeLongUtf($string);
        $test = $stream->getStream();
        $this->assertContains($string, $test);
    }
    
    public function testBigEndianByte(){
        //-128 + 1 = -127
        $stream = new Zend_Amf_Util_BinaryStream("\x81");
        $int = $stream->readByte();
        $this->assertEquals($int, -127);
    }
    
    public function testLittleEndianByte(){
        $stream = new Zend_Amf_Util_BinaryStream("\x81");
        $stream->setBigEndian(false);
        $int = $stream->readByte();
        $this->assertEquals($int, -127);
    }
    
    public function testWriteByte(){
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->writeByte(-127);
        $this->assertEquals($stream->getStream(), "\x81");
    }
    
    public function testWriteUnsignedByte(){
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->writeUnsignedByte(129);
        $this->assertEquals($stream->getStream(), "\x81");
    }
    
    public function testReadBigEndianUnsignedByte(){
        //128 + 1 = 129
        $stream = new Zend_Amf_Util_BinaryStream("\x81");
        $int = $stream->readUnsignedByte();
        $this->assertEquals($int, 129);
    }
    
    public function testReadLittleEndianUnsignedByte(){
        $stream = new Zend_Amf_Util_BinaryStream("\x81");
        $stream->setBigEndian(false);
        $int = $stream->readUnsignedByte();
        $this->assertEquals($int, 129);
    }
    
    public function testReadBigEndianInt(){
        //1*65536+2*256+4   = 66052
        $stream = new Zend_Amf_Util_BinaryStream("\x80\x01\x02\x04");
        $int = $stream->readInt();
        $this->assertEquals($int, -2147417596);
    }
    
    public function testWriteBigEndianInt(){
        //-2^31 + 1*65536+2*256+4   = -2147417596
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->writeInt(-2147417596);
        $this->assertEquals($stream->getStream(), "\x80\x01\x02\x04");
    }
    
    public function testReadLittleEndianInt(){
        //-2^31 + 1*65536+2*256+4   = -2147417596
        $stream = new Zend_Amf_Util_BinaryStream("\x04\x02\x01\x80");
        $stream->setBigEndian(false);
        $int = $stream->readInt();
        $this->assertEquals($int, -2147417596);
    }
    
    public function testWriteLittleEndianInt(){
        //-2^31 + 1*65536+2*256+4   = -2147417596
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->setBigEndian(false);
        $stream->writeInt(-2147417596);
        $this->assertEquals($stream->getStream(), "\x04\x02\x01\x80");
    }
    
    
    public function testReadBigEndianShort(){
        //-128*256 +1   = -32767
        $stream = new Zend_Amf_Util_BinaryStream("\x80\x01");
        $int = $stream->readShort();
        $this->assertEquals($int, -32767);
    }
    
    public function testWriteBigEndianShort(){
        //-128*256 +1   = -32767
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->writeShort(-32767);
        $this->assertEquals($stream->getStream(), "\x80\x01");
    }
    
    public function testReadLittleEndianShort(){
        //-128*256 +1   = -32767
        $stream = new Zend_Amf_Util_BinaryStream("\x01\x80");
        $stream->setBigEndian(false);
        $int = $stream->readShort();
        $this->assertEquals($int, -32767);
    }
    
    public function testWriteLitteEndianShort(){
        //-128*256 +1   = -32767
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->setBigEndian(false);
        $stream->writeShort(-32767);
        $this->assertEquals($stream->getStream(), "\x01\x80");
    }
    
    public function testReadBigEndianUnsignedShort(){
        //128*256 + 1 = 32769
        $stream = new Zend_Amf_Util_BinaryStream("\x80\x01");
        $int = $stream->readUnsignedShort();
        $this->assertEquals($int, 32769);
    }
    
    public function testWriteBigEndianUnsignedShort(){
        //128*256 + 1 = 32769
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->writeShort(32769);
        $this->assertEquals($stream->getStream(), "\x80\x01");
    }
    
    public function testLittleEndianUnsignedShort(){
        //128*256 + 1 = 32769
        $stream = new Zend_Amf_Util_BinaryStream("\x01\x80");
        $stream->setBigEndian(false);
        $int = $stream->readUnsignedShort();
        $this->assertEquals($int, 32769);
    }
    
    public function testWriteLittleEndianUnsignedShort(){
        //128*256 + 1 = 32769
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->setBigEndian(false);
        $stream->writeShort(32769);
        $this->assertEquals($stream->getStream(), "\x01\x80");
    }
    
    public function testFloat(){
        $stream = new Zend_Amf_Util_BinaryStream('');
        $stream->writeFloat(1.5);
        $data = $stream->getStream();
        $stream = new Zend_Amf_Util_BinaryStream($data);
        $f = $stream->readFloat();
        $this->assertEquals($f, 1.5);
        
        $data = strrev($data);
        $stream = new Zend_Amf_Util_BinaryStream($data);
        $stream->setBigEndian(false);
        $f = $stream->readFloat();
        $this->assertEquals($f, 1.5);
        
    }
}

if (PHPUnit_MAIN_METHOD == 'Zend_Amf_Util_BinaryStreamTest::main') {
    Zend_Amf_Util_BinaryStreamTest::main();
}
