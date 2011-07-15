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
 * @subpackage Parse_Amf3
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/** Zend_Amf_Parse_Deserializer */
require_once 'Zend/Amf/Parse/Deserializer.php';

/** Zend_Amf_TypeMapper */
require_once 'Zend/Amf/TypeMapperInterface.php';

/**
 * Read an AMF3 input stream and convert it into PHP data types.
 *
 * @todo       readObject to handle Typed Objects
 * @todo       readXMLStrimg to be implemented.
 * @todo       Class could be implemented as Factory Class with each data type it's own class.
 * @package    Zend_Amf
 * @subpackage Parse_Amf3
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Amf_Parse_Amf3_DataOutputWrapper implements Zend_Amf_Util_DataOutputInterface
{
    /**
     * @var Zend_Amf_Parse_OutputStream
     */
    protected $_stream;
    
    /**
     * @var Zend_Amf_Parse_Amf3_Serializer
     */
    protected $_serializer;
    
    public function __construct(Zend_Amf_Parse_Amf3_Serializer $serializer)
    {
        $this->_serializer = $serializer;
        $this->_stream = $serializer->getStream();
    }
    
    /*
     * reads a boolean from the stream
     */
    public function writeBoolean($value) {
        $this->_stream->writeByte($value ? 1 : 0);
    }
    
    /*
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
        $this->_serializer->writeTypeMarker($value);
    }
}
