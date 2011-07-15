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
class Zend_Amf_Parse_Amf3_DataInputWrapper implements Zend_Amf_Util_DataInputInterface
{
    /**
     * @var Zend_Amf_Parse_InputStream
     */
    protected $_stream;
    
    /**
     * @var Zend_Amf_Parse_Amf3_Deserializer
     */
    protected $_deserializer;
    
    public function __construct(Zend_Amf_Parse_Amf3_Deserializer $deserializer)
    {
        $this->_deserializer = $deserializer;
        $this->_stream = $deserializer->getStream();
    }
    
    /*
     * reads a boolean from the stream
     */
    public function readBoolean() {
        return ($this->readUnsignedByte() > 0);
    }
    
    /*
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
        return $this->_deserializer->readTypeMarker();
    }
}
