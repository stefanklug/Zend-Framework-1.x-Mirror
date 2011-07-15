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
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Interface matching flash.utils.IDataInput
 *
 * @package    Zend_Amf
 * @subpackage Util
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Zend_Amf_Util_DataInputInterface
{
    /**
     * reads a boolean from the stream
     */
    function readBoolean();
    
    /**
     * Reads length bytes from the stream
     * 
     * @return string
     */
    function readBytes($length);

    /**
     * Reads a signed byte
     *
     * @return int Value is in the range of -128 to 127.
     */
    function readByte();
    

    /**
     * Reads a signed 32-bit integer from the data stream.
     *
     * @return int Value is in the range of -2147483648 to 2147483647
     */
    function readInt();
    
    /**
     * Reads a signed 16-bit integer from the data stream.
     *
     * @return int Value is in the range of -32768 to 32767
     */
    function readShort();


    /**
     * Reads a UTF-8 string from the data stream
     *
     * @return string A UTF-8 string produced by the byte representation of characters
     */
    function readUtf();


    /**
     * Read a 16 bit unsigned short.
     *
     * @todo   This could use the unpack() w/ S,n, or v
     * @return double
     */
    function readUnsignedShort();

    /**
     * Reads an IEEE 754 double-precision floating point number from the data stream.
     *
     * @return double Floating point number
     */
    function readDouble();
    
    
    /**
     * Reads an IEEE 754 single-precision floating point number from the data stream.
     *
     * @return double Floating point number
     */
    function readFloat();

    /**
     * Reads an Object from the data stream, encoded in AMF serialization format
     *
     * @return double Floating point number
     */
    function readObject();
    

}
