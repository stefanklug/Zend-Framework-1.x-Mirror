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
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: Deserializer.php 23775 2011-03-01 17:25:24Z ralph $
 */

/** Zend_Amf_Parse_InputStreamInterface */
require_once 'Zend/Amf/Parse/InputStreamInterface.php';

/** Zend_Amf_TypeMapperInterface */
require_once 'Zend/Amf/TypeMapperInterface.php';

/**
 * Abstract cass that all deserializer must implement.
 *
 * Logic for deserialization of the AMF envelop is based on resources supplied
 * by Adobe Blaze DS. For and example of deserialization please review the BlazeDS
 * source tree.
 *
 * @see        http://opensource.adobe.com/svn/opensource/blazeds/trunk/modules/core/src/java/flex/messaging/io/amf/
 * @package    Zend_Amf
 * @subpackage Parse
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
abstract class Zend_Amf_Parse_Deserializer
{
    /**
     * The raw string that represents the AMF request.
     *
     * @var Zend_Amf_Parse_InputStream
     */
    protected $_stream;
    
    /**
     * The TypeMapper associated with the Deserializer
     *
     * @var Zend_Amf_TypeMapperInterface
     */
    protected $_typeMapper;

    /**
     * Constructor
     *
     * @param  Zend_Amf_Parse_InputStream $stream
     * @return void
     */
    public function __construct(Zend_Amf_Parse_InputStreamInterface $stream, Zend_Amf_TypeMapperInterface $typeMapper)
    {
        $this->_stream = $stream;
        $this->_typeMapper = $typeMapper;
    }
    
    /**
     * Returns the current stream
     * 
     * @return Zend_Amf_Parse_InputStream
     */
    public function getStream()
    {
        return $this->_stream;
    }

    /**
     * Checks for AMF marker types and calls the appropriate methods
     * for deserializing those marker types. Markers are the data type of
     * the following value.
     *
     * @param  int $typeMarker
     * @return mixed Whatever the data type is of the marker in php
     */
    public abstract function readTypeMarker($markerType = null);
}
