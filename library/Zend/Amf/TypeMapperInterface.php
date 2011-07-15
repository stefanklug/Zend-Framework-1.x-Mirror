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
 * Defines the interface for the TypeMapper used by Zend_Amf_Server
 *
 * @package    Zend_Amf
 * @subpackage Parse
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
interface Zend_Amf_TypeMapperInterface
{
    /**
     * Gets the AS class name for a given php object or class name
     * If a string is passed to this function it is expected to be a php class name. Otherwise the object itself should be inspected.
     * It is important, that this function also works with strings, as the Zend_Amf_Adobe_Introspector needs this functionality
     * @param mixed $object
     * @return string the remote class name, or an empty string, if this class can not be mapped
     */
    function getRemoteClassName($objectOrClassname);
    
    /**
     * Gets the php class name for a given remote class name
     * @param $remoteClassName
     * @return string the remote class name, or an empty string, if this class can not be mapped
     */
    function getLocalClassName($remoteClassName);
    
    /**
     * Checks if the given object should be treated as externalizable by the (de)serializer
     * @param $object
     * @return boolean
     */
    function isExternalizable($object);
    
    /**
     * This is invoked by the deserializer, if isExternalizable() returned true for the given object.
     * The implementation must read the given object from the input stream.
     * @param $object
     * @param $input
     */
    function readExternal($object, Zend_Amf_Util_DataInputInterface $input);
    
    /**
     * This is invoked by the serializer, if isExternalizable() returned true for the given object.
     * The implementation must write the given object to the output stream.
     * @param $object
     * @param $input
     */
    function writeExternal($object, Zend_Amf_Util_DataOutputInterface $output);

    /**
     * This function must convert the given resource to any object which is fed to the AMF serializer
     * @param $resource
     * @return converted resource
     */
    function handleResource($resource);
    
    /**
     * returns the php service class name for the requested service name
     * @param $requestedService
     */
    function getServiceClassName($requestedService);
}
