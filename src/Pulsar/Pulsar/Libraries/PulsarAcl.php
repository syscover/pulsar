<?php namespace Pulsar\Pulsar\Libraries;
/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL.
 * @license		
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource  Librarie extend from Zend Acl
 */

use Pulsar\Pulsar\Models\Resource,
    Pulsar\Pulsar\Models\Permission,
    Zend\Permissions\Acl\Acl,
    Zend\Permissions\Acl\Role\GenericRole as Role,
    Zend\Permissions\Acl\Resource\GenericResource as AclResource;

class PulsarAcl extends Acl
{
    /**
     *  Function instance the acl of a particular profile
     *
     * @access	public
     * @return	\Zend\Permissions\Acl\Acl
     */
    public static function getProfileAcl($profile)
    {
        $acl            = new PulsarAcl();
        $resources      = Resource::get();
        $permissions    = Permission::getRecords($profile);
        
        $acl->addRole(new Role($profile));
        foreach($resources as $resource)
        {
            $acl->addResource(new AclResource($resource->id_007));
        }
        foreach($permissions as $permission)
        {
            $acl->allow($profile, $permission->recurso_009, $permission->accion_009);
        }
        return $acl;
    }
    
    /**
     *  Function that instantiates an array in javascript to identify actions that allow resource Casda
     *
     * @access	public
     * @return	array
     */
    public function getJsAccionesAllowed($profile, $resource, $actions)
    {
        $actionsAllowed = '[';
        $flag = false;
        foreach ($actions as $action)
        {
            if(parent::isAllowed($profile, $resource, $action->id_008))
            {
                if($flag) $actionsAllowed .=',';
                $actionsAllowed .= '"'.$action->id_008.'"';
                $flag =true;
            }
        }
        
        $actionsAllowed .= ']';
        return $actionsAllowed;    
    }
}