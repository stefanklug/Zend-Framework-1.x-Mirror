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
 * @subpackage Value
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: ByteArray.php 23775 2011-03-01 17:25:24Z ralph $
 */

/**
 * Wrapper class to store an AMF3 flash.utils.ByteArray
 *
 * @package    Zend_Amf
 * @subpackage Value
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Amf_Value_ByteArray
{
    /**
     * @todo we use the output stream, as the serializers need this type. This is a hack and implys, that the OutputStream is also good for reading
     * @var Zend_Amf_Util_BinaryStream Data
     */
    protected $_stream;
    protected $_objectEncoding;
    
    protected $_typeMapper;

    /**
     * Create a ByteArray
     *
     * @param  string $data
     * @return void
     */
    public function __construct($data='')
    {
        $this->_objectEncoding = Zend_Amf_Constants::AMF3_OBJECT_ENCODING;
        $this->setData($data);
    }
    
    public function getObjectEncoding() {
        return $this->_objectEncoding;
    }
    
    public function setObjectEncoding($encoding) {
        $this->_objectEncoding = $encoding;
    }
    
    public function setTypeMapper(Zend_Amf_TypeMapperInterface $typeMapper) {
        $this->_typeMapper = $typeMapper;
    }
    
    public function getTypeMapper() {
        return $this->_typeMapper;
    }
    
    

    /**
     * Return the byte stream
     *
     * @return string
     */
    public function getData()
    {
        return $this->_stream->getStream();
    }
    
	/**
     * Resets the ByteArray with the given data
     *
     * @param string $data
     */
    public function setData($data)
    {
        $this->_stream = new Zend_Amf_Util_BinaryStream($data);
    }
    
    
    /**
     * reads a boolean from the stream
     */
    public function writeBoolean($value) {
        $this->_stream->writeByte($value ? 1 : 0);
    }
    
    /**
     * Reads length bytes from the stream
     * 
     * @return string
     */
    function writeBytes($bytes) 
    {
        $this->_stream->writeBytes($bytes);
    }

    /**
     * Reads a signed byte
     *
     * @return int Value is in the range of -128 to 127.
     */
    function writeByte($value)
    {
        $this->_stream->writeByte($value);
    }
    

    /**
     * Reads a signed 32-bit integer from the data stream.
     *
     * @return int Value is in the range of -2147483648 to 2147483647
     */
    function writeInt($value)
    {
        $this->_stream->writeInt($value);
    }
    
    /**
     * Reads a signed 16-bit integer from the data stream.
     *
     * @return int Value is in the range of -32768 to 32767
     */
    public function writeShort($value)
    {
        $this->_stream->writeShort($value);
    }


    /**
     * Reads a UTF-8 string from the data stream
     *
     * @return string A UTF-8 string produced by the byte representation of characters
     */
    public function writeUtf($value)
    {
        $this->_stream->writeUtf($value);
    }


    /**
     * Read a 16 bit unsigned short.
     *
     * @return int
     */
    public function writeUnsignedShort($value)
    {
        $this->_stream->writeUnsignedShort($value);
    }

    /**
     * Reads an IEEE 754 double-precision floating point number from the data stream.
     *
     * @return double Floating point number
     */
    public function writeDouble($value)
    {
        $this->_stream->writeDouble($value);
    }
    
    
    /**
     * Reads an IEEE 754 single-precision floating point number from the data stream.
     *
     * @return double Floating point number
     */
    function writeFloat($value)
    {
        $this->_stream->writeFloat($value);
    }

    /**
     * Reads an Object from the data stream, encoded in AMF serialization format
     *
     * @return 
     */
    function writeObject($value)
    {
        if(!$this->_typeMapper) {
            require_once 'Zend/Amf/Exception.php';
            throw new Zend_Amf_Exception("ByteArray can't write object without a type mapper");
        }
        
        switch($this->_objectEncoding) {
            case Zend_Amf_Constants::AMF0_OBJECT_ENCODING:
                $serializer = new Zend_Amf_Parse_Amf0_Serializer($this->_stream, $this->_typeMapper);
                break;
            case Zend_Amf_Constants::AMF3_OBJECT_ENCODING:
                $serializer = new Zend_Amf_Parse_Amf3_Serializer($this->_stream, $this->_typeMapper);
                break;
            default:
                require_once 'Zend/Amf/Exception.php';
                throw new Zend_Amf_Exception("Unknown object encoding ".$this->_objectEncoding);
        }
        
        $serializer->writeTypeMarker($value);
    }
    
	/**
     * reads a boolean from the stream
     */
    public function readBoolean() {
        return ($this->readUnsignedByte() > 0);
    }
    
    /**
     * Reads length bytes from the stream
     * 
     * @return string
     */
    function readBytes($length) 
    {
        return $this->_stream->readBytes($length);
    }

    /**
     * Reads a signed byte
     *
     * @return int Value is in the range of -128 to 127.
     */
    function readByte()
    {
        return $this->_stream->readByte();
    }
    

    /**
     * Reads a signed 32-bit integer from the data stream.
     *
     * @return int Value is in the range of -2147483648 to 2147483647
     */
    function readInt()
    {
        return $this->_stream->readInt();
    }
    
    /**
     * Reads a signed 16-bit integer from the data stream.
     *
     * @return int Value is in the range of -32768 to 32767
     */
    public function readShort()
    {
        return $this->_stream->readShort();
    }


    /**
     * Reads a UTF-8 string from the data stream
     *
     * @return string A UTF-8 string produced by the byte representation of characters
     */
    function readUtf()
    {
        return $this->_stream->readUtf();
    }


    /**
     * Read a 16 bit unsigned short.
     *
     * @return int
     */
    public function readUnsignedShort()
    {
        return $this->_stream->readUnsignedShort();
    }

    /**
     * Reads an IEEE 754 double-precision floating point number from the data stream.
     *
     * @return double Floating point number
     */
    public function readDouble()
    {
        return $this->_stream->readDouble();
    }
    
    
    /**
     * Reads an IEEE 754 single-precision floating point number from the data stream.
     *
     * @return double Floating point number
     */
    function readFloat()
    {
        return $this->_stream->readFloat();
    }

    /**
     * Reads an Object from the data stream, encoded in AMF serialization format
     *
     * @return 
     */
    function readObject()
    {
        if(!$this->_typeMapper) {
            require_once 'Zend/Amf/Exception.php';
            throw new Zend_Amf_Exception("ByteArray can't read object without a type mapper");
        }
        
        WW_Log::debug("arr %s",print_rs($this->_stream));
        
        switch($this->_objectEncoding) {
            case Zend_Amf_Constants::AMF0_OBJECT_ENCODING:
                $deserializer = new Zend_Amf_Parse_Amf0_Deserializer($this->_stream, $this->_typeMapper);
                break;
            case Zend_Amf_Constants::AMF3_OBJECT_ENCODING:
                $deserializer = new Zend_Amf_Parse_Amf3_Deserializer($this->_stream, $this->_typeMapper);
                break;
            default:
                require_once 'Zend/Amf/Exception.php';
                throw new Zend_Amf_Exception("Unknown object encoding ".$this->_objectEncoding);
        }
        
        return $deserializer->readTypeMarker();
    }
}
