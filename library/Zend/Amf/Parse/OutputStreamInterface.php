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
 * @subpackage Parse
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * Iterate at a binary level through the AMF response
 *
 * @uses       Zend_Amf_Util_BinaryStream
 * @package    Zend_Amf
 * @subpackage Parse
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Zend_Amf_Parse_OutputStreamInterface
{   
    /**
     * writes the given bytes to the stream
     * 
     * @param string $bytes
     */
    function writeBytes($bytes);

    /**
     * Writes a signed Byte
     *
     * @param int $value
     */
    function writeByte($value);
    

    /**
     * Writes a signed 32-bit integer to the data stream.
     *
     * @Param int $value
     */
    function writeInt($value);
    
    /**
     * Writes a signed 16-bit integer to the data stream.
     *
     * @Param int $value
     */
    function writeShort($value);


    /**
     * writes a UTF-8 string from the data stream
     *
     * @param $value
     */
    function writeUtf($value);


    /**
     * Writes a unsigned 16-bit integer to the data stream.
     *
     * @Param int $value
     */
    function writeUnsignedShort($value);

    /**
     * Writes an IEEE 754 double-precision floating point number to the data stream.
     *
     * @Param double $value
     */
    function writeDouble($value);
    
    
    /**
     * Writes an IEEE 754 single-precision floating point number to the data stream.
     *
     * @Param double $value
     */
    function writeFloat($value);
}
