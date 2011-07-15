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
 * @version    $Id: TypeLoader.php 23775 2011-03-01 17:25:24Z ralph $
 */

/**
 * @see Zend_Amf_Value_Messaging_AcknowledgeMessage
 */
require_once 'Zend/Amf/TypeMapperInterface.php';

/**
 * @see Zend_Amf_Value_Messaging_AcknowledgeMessage
 */
require_once 'Zend/Amf/Value/Messaging/AcknowledgeMessage.php';
/**
 * @see Zend_Amf_Value_Messaging_AsyncMessage
 */
require_once 'Zend/Amf/Value/Messaging/AsyncMessage.php';
/**
 * @see Zend_Amf_Value_Messaging_CommandMessage
 */
require_once 'Zend/Amf/Value/Messaging/CommandMessage.php';
/**
 * @see Zend_Amf_Value_Messaging_ErrorMessage
 */
require_once 'Zend/Amf/Value/Messaging/ErrorMessage.php';
/**
 * @see Zend_Amf_Value_Messaging_RemotingMessage
 */
require_once 'Zend/Amf/Value/Messaging/RemotingMessage.php';

/**
 * Loads a local class and executes the instantiation of that class.
 *
 * @todo       PHP 5.3 can drastically change this class w/ namespace and the new call_user_func w/ namespace
 * @package    Zend_Amf
 * @subpackage Parse
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Amf_TypeMapper implements Zend_Amf_TypeMapperInterface
{

    /**
     * @var array AMF class map
     */
    protected $_classMap;

    /**
     * @var array Default class map
     */
    protected static $_obligateClassMap = array(
        'flex.messaging.messages.AcknowledgeMessage' => 'Zend_Amf_Value_Messaging_AcknowledgeMessage',
        'flex.messaging.messages.ErrorMessage'       => 'Zend_Amf_Value_Messaging_AsyncMessage',
        'flex.messaging.messages.CommandMessage'     => 'Zend_Amf_Value_Messaging_CommandMessage',
        'flex.messaging.messages.ErrorMessage'       => 'Zend_Amf_Value_Messaging_ErrorMessage',
        'flex.messaging.messages.RemotingMessage'    => 'Zend_Amf_Value_Messaging_RemotingMessage',
    );
    

    /**
     * @var Zend_Loader_PluginLoader_Interface
     */
    protected $_resourceLoader = null;
    
    
    public function __construct()
    {
        $this->resetClassMap();
    }
    
    


    /**
     * Load the mapped class type into a callback.
     *
     * @param  string $className
     * @return object|false
     */
    public function getLocalClassName($remoteClassName)
    {
        $class = false;
        if(isset($this->_classMap[$remoteClassName])) {
            $class = $this->_classMap[$remoteClassName];
        }
        
        if(!$class) {
            $class = str_replace('.', '_', $remoteClassName);
        }
        if (!class_exists($class)) {
            return "stdClass";
        }
        return $class;
    }

    
    public function getRemoteClassName($object)
    {
        //we try name based lookup first
        if(is_object($object)) {
            $className = get_class($object);
        } else {
            $className = (string)$object;
        }
        
        //table based lookup
        if($result = array_search($className, $this->_classMap)){
            return $result;
        }
        
        //_explicitType based lookup
        if(is_object($object)) {
            if(isset($object->_explicitType)) {
                return $object->_explicitType;
            }
        } else {
            if(class_exists($className)) {
                $vars = get_class_vars($object);
    
                if (isset($vars['_explicitType'])) {
                    return $vars['_explicitType'];
                }
            }
        }
        
        if(is_object($object) && method_exists($object, 'getASClassName')) {
            return $object->getASClassName();
        }
        
        if(is_object($object) && $object instanceof stdClass){
            return '';
        }
        
        return $className;
    }
    
    
    public function isExternalizable($object)
    {
        return (is_object($object) && $object instanceof Zend_Amf_Util_ExternalizableInterface);
    }
    
    /**
     * This is invoked by the deserializer, if isExternalizable() returned true for the given object.
     * The implementation must read the given object from the input stream.
     * @param $object
     * @param $input
     */
    public function readExternal($object, Zend_Amf_Util_DataInputInterface $input)
    {
        $object->readExternal($input);
    }
    
    /**
     * This is invoked by the serializer, if isExternalizable() returned true for the given object.
     * The implementation must write the given object to the output stream.
     * @param $object
     * @param $input
     */
    function writeExternal($object, Zend_Amf_Util_DataOutputInterface $output)
    {
        $object->writeExternal($output);
    }

    /**
     * Map PHP class names to ActionScript class names
     *
     * Allows users to map the class names of there action script classes
     * to the equivelent php class name. Used in deserialization to load a class
     * and serialiation to set the class name of the returned object.
     *
     * @param  string $asClassName
     * @param  string $phpClassName
     * @return void
     */
    public function setClassMap($asClassName, $phpClassName)
    {
        $this->_classMap[$asClassName] = $phpClassName;
    }
    
	/**
     * Reset type map
     *
     * @return void
     */
    public function resetClassMap()
    {
        $this->_classMap = self::$_obligateClassMap;
        //the ArrayCollection is also added as default
        $this->setClassMap('flex.messaging.io.ArrayCollection', 'Zend_Amf_Value_Messaging_ArrayCollection');
        $this->setClassMap('flex.messaging.io.ArrayList', 'Zend_Amf_Value_Messaging_ArrayList');
    }


    /**
     * Set loader for resource type handlers
     *
     * @param Zend_Loader_PluginLoader_Interface $loader
     */
    public function setResourceLoader(Zend_Loader_PluginLoader_Interface $loader)
    {
        $this->_resourceLoader = $loader;
    }

    /**
     * Add directory to the list of places where to look for resource handlers
     *
     * @param string $prefix
     * @param string $dir
     */
    public function addResourceDirectory($prefix, $dir)
    {
        if($this->_resourceLoader) {
            $this->_resourceLoader->addPrefixPath($prefix, $dir);
        }
    }

    /**
     * Get plugin class that handles this resource
     *
     * @param resource $resource Resource type
     * @return string Class name
     */
    private function getResourceParser($resource)
    {
        if($this->_resourceLoader) {
            $type = preg_replace("/[^A-Za-z0-9_]/", " ", get_resource_type($resource));
            $type = str_replace(" ","", ucwords($type));
            return $this->_resourceLoader->load($type);
        }
        return false;
    }

    /**
     * Convert resource to a serializable object
     *
     * @param resource $resource
     * @return mixed
     */
    public function handleResource($resource)
    {
        if(!$this->_resourceLoader) {
            require_once 'Zend/Amf/Exception.php';
            throw new Zend_Amf_Exception('Unable to handle resources - resource plugin loader not set');
        }
        
        try {
            while(is_resource($resource)) {
                $resclass = $this->getResourceParser($resource);
                if(!$resclass) {
                    require_once 'Zend/Amf/Exception.php';
                    throw new Zend_Amf_Exception('Can not serialize resource type: '. get_resource_type($resource));
                }
                $parser = new $resclass();
                if(is_callable(array($parser, 'parse'))) {
                    $resource = $parser->parse($resource);
                } else {
                    require_once 'Zend/Amf/Exception.php';
                    throw new Zend_Amf_Exception("Could not call parse() method on class $resclass");
                }
            }
            return $resource;
        } catch(Zend_Amf_Exception $e) {
            throw new Zend_Amf_Exception($e->getMessage(), $e->getCode(), $e);
        } catch(Exception $e) {
            require_once 'Zend/Amf/Exception.php';
            throw new Zend_Amf_Exception('Can not serialize resource type: '. get_resource_type($resource), 0, $e);
        }
    }
    
    public function getServiceClassName($requestedService) {
        $class = false;
        
        if(isset($this->_classMap[$requestedService])){
            $class = $this->_classMap[$requestedService];
        }
        
        if(!$class) {
            $class = str_replace('.', '_', $requestedService);
        }
        
        return $class;
    }
}
