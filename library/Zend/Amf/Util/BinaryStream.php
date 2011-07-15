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
 * @subpackage Util
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: BinaryStream.php 23775 2011-03-01 17:25:24Z ralph $
 */

/** Zend_Amf_Parse_InputStreamInterface */
require_once 'Zend/Amf/Parse/InputStreamInterface.php';

/** Zend_Amf_Parse_OutputStreamInterface */
require_once 'Zend/Amf/Parse/OutputStreamInterface.php';

/**
 * Utility class to walk through a data stream byte by byte with conventional names
 *
 * @package    Zend_Amf
 * @subpackage Util
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Amf_Util_BinaryStream implements Zend_Amf_Parse_InputStreamInterface, Zend_Amf_Parse_OutputStreamInterface
{
    /**
     * @var string Byte stream
     */
    protected $_stream;

    /**
     * @var int Length of stream
     */
    protected $_streamLength;

    /**
     * @var bool BigEndian encoding?
     */
    protected $_bigEndian;
    
    /**
     * @var bool true if the current system is big endian
     */
    protected $_systemIsBigEndian;

    /**
     * @var int Current position in stream
     */
    protected $_needle;

    /**
     * Constructor
     *
     * Create a reference to a byte stream that is going to be parsed or created
     * by the methods in the class. Detect if the class should use big or
     * little Endian encoding.
     *
     * @param  string data to read from, or write to
     * @return void
     */
    public function __construct($stream='')
    {
        if (!is_string($stream)) {
            require_once 'Zend/Amf/Exception.php';
            throw new Zend_Amf_Exception('Inputdata is not of type String');
        }

        $this->_stream       = $stream;
        $this->_needle       = 0;
        $this->_streamLength = strlen($stream);
        $this->_bigEndian = true;
        $this->_systemIsBigEndian = (pack('l', 1) === "\x00\x00\x00\x01");
    }
    
    /**
     * returns the position where reading or writing occurs
     * @return int
     */
    public function getPosition() {
        return $this->_needle;
    }
    
    /**
     * sets the position, where the next reading or writing operation occurs
     * @param int $position
     */
    public function setPosition($position) {
        if($position < 0 || $position > strlen($this->stream)) {
            require_once 'Zend/Amf/Exception.php';
            throw new Zend_Amf_Exception("Can't set stream position out of bounds");
        }
        $this->_needle = $position;
    }
    
    
    /**
     * Enables or disables big endian. This controls further reading and writing to and from the stream. 
     * The stream defaults to big endian as this is network byte order
     * @param boolean $bool
     */
    public function setBigEndian($bool) {
        $this->_bigEndian = $bool;
    }
    
    /**
     * Returns true, when the current mode is big endian, otherwise false
     * @return boolean
     */
    public function getBigEndian() {
        return $this->_bigEndian;
    }
    
    public function getSystemIsBigEndian() {
        return $this->_sytemIsBigEndian;
    }
    
    protected function _packAndWrite($value, $format, $adjustEndianess) {
        $bytes = pack($format, $value);
        if($adjustEndianess && $this->_systemIsBigEndian != $this->_bigEndian) {
            $bytes = strrev($bytes);
        }
        $this->writeBytes($bytes);
    }
    
    protected function _readAndUnpack($length, $format, $adjustEndianess) {
        $bytes = $this->readBytes($length);
         if($adjustEndianess && $this->_systemIsBigEndian != $this->_bigEndian) {
            $bytes = strrev($bytes);
        }
        $data = unpack($format.'v', $bytes);
        return $data['v'];
    }
    
    
    
    

    /**
     * Returns the current stream
     *
     * @return string
     */
    public function getStream()
    {
        return $this->_stream;
    }

    /**
     * Read the number of bytes in a row for the length supplied.
     *
     * @todo   Should check that there are enough bytes left in the stream we are about to read.
     * @param  int $length
     * @return string
     * @throws Zend_Amf_Exception for buffer underrun
     */
    public function readBytes($length)
    {
        if (($length + $this->_needle) > $this->_streamLength) {
            require_once 'Zend/Amf/Exception.php';
            throw new Zend_Amf_Exception('Buffer underrun at needle position: ' . $this->_needle . ' while requesting length: ' . $length);
        }
        $bytes = substr($this->_stream, $this->_needle, $length);
        $this->_needle += $length;
        return $bytes;
    }

    /**
     * Write any length of bytes to the stream
     * the write position is incremented by the number of bytes written
     *
     * @param  string $bytes
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeBytes($bytes)
    {
        $len = strlen($bytes);
        $this->_stream = substr_replace($this->_stream, $bytes, $this->_needle, strlen($bytes));
        $this->_needle += $len;
        return $this;
    }

    /**
     * Reads a signed byte
     *
     * @return int Value is in the range of -128 to 127.
     */
    public function readByte()
    {
        return $this->_readAndUnpack(1, 'c', true);
    }

    /**
     * Writes the passed string into a signed byte on the stream.
     *
     * @param  string $stream
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeByte($value)
    {
        $this->_packAndWrite($value, 'c', true);
        return $this;
    }
    
    public function readUnsignedByte()
    {
        return $this->_readAndUnpack(1, 'C', true);
    }
    
    
    public function writeUnsignedByte($value)
    {
        $this->_packAndWrite($value, 'C', true);
        return $this;
    }

    /**
     * Reads a signed 32-bit integer from the data stream.
     *
     * @return int Value is in the range of -2147483648 to 2147483647
     */
    public function readInt()
    {
        return $this->readLong();
    }

    /**
     * Write an the integer to the output stream as a 32 bit signed integer
     *
     * @param  int $stream
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeInt($value)
    {
        $this->writeLong($value);
        return $this;
    }

    /**
     * Reads a UTF-8 string from the data stream
     *
     * @return string A UTF-8 string produced by the byte representation of characters
     */
    public function readUtf()
    {
        $length = $this->readUnsignedShort();
        return $this->readBytes($length);
    }

    /**
     * Wite a UTF-8 string to the outputstream
     *
     * @param  string $stream
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeUtf($stream)
    {
        $this->writeUnsignedShort(strlen($stream));
        $this->writeBytes($stream);
        return $this;
    }


    /**
     * Read a long UTF string
     *
     * @return string
     */
    public function readLongUtf()
    {
        $length = $this->readLong();
        return $this->readBytes($length);
    }

    /**
     * Write a long UTF string to the buffer
     *
     * @param  string $stream
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeLongUtf($stream)
    {
        $this->writeLong(strlen($stream));
        $this->writeBytes($stream);
        return $this;
    }

    /**
     * Read a long numeric value
     *
     * @return double
     */
    public function readLong()
    {
        return $this->_readAndUnpack(4, 'l', true);
    }

    /**
     * Write long numeric value to output stream
     *
     * @param  int|string $value
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeLong($value)
    {
        $this->_packAndWrite($value, 'l', true);
        return $this;
    }
    
    /**
     * Read a 16 bit signed short.
     *
     * @return int
     */
    public function readShort()
    {
        return $this->_readAndUnpack(2, 's', true);
    }
    
    /**
     * Write a 16 bit signed short.
     * @param int|string $value
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeShort($value)
    {
        $this->_packAndWrite($value, 's', true);
        return $this;
    }

    /**
     * Read a 16 bit unsigned short.
     *
     * @return int
     */
    public function readUnsignedShort()
    {
        return $this->_readAndUnpack(2, 'S', true);
    }
    
    /**
     * Write a 16 bit unsigned short.
     * @param int|string $value
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeUnsignedShort($value)
    {
        $this->_packAndWrite($value, 'S', true);
        return $this;
    }

    /**
     * Reads an IEEE 754 double-precision floating point number from the data stream.
     *
     * @return double Floating point number
     */
    public function readDouble()
    {
        return $this->_readAndUnpack(8, 'd', true);
    }

    /**
     * Writes an IEEE 754 double-precision floating point number from the data stream.
     *
     * @param  string|double $stream
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeDouble($value)
    {
        $this->_packAndWrite($value, 'd', true);
        return $this;
    }
    
	/**
     * Reads an IEEE 754 single-precision floating point number from the data stream.
     *
     * @return float Floating point number
     */
    public function readFloat()
    {
        return $this->_readAndUnpack(4, 'f', true);
    }

    /**
     * Writes an IEEE 754 single-precision floating point number from the data stream.
     *
     * @param  string|float $stream
     * @return Zend_Amf_Util_BinaryStream
     */
    public function writeFloat($value)
    {
        $this->_packAndWrite($value, 'f', true);
        return $this;
    }

}
