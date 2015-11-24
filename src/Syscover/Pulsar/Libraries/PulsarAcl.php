<?php namespace Syscover\Pulsar\Libraries;

/**
 * @package		Pulsar
 * @author		Jose Carlos Rodríguez Palacín
 * @copyright   Copyright (c) 2015, SYSCOVER, SL.
 * @license		
 * @link		http://www.syscover.com
 * @since		Version 1.0
 * @filesource  Librarie extend from Zend Acl
 */

use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\Permission;
use Zend\Permissions\Acl\Exception;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as AclResource;

class PulsarAcl extends Acl
{
    /**
     *  Function instance the acl of a particular profile
     *
     * @access	public
     * @param   integer     $profile
     * @return	\Zend\Permissions\Acl\Acl
     */
    public static function getProfileAcl($profile)
    {
        $acl            = new PulsarAcl();
        $resources      = Resource::get();
        $permissions    = Permission::getRecord($profile);
        
        $acl->addRole(new Role($profile));
        foreach($resources as $resource)
        {
            $acl->addResource(new AclResource($resource->id_007));
        }
        foreach($permissions as $permission)
        {
            $acl->allow($profile, $permission->resource_009, $permission->action_009);
        }
        return $acl;
    }
    
    /**
     *  Function that instantiates an array in javascript to identify actions that allow resource
     *
     * @access	public
     * @param   integer     $profile
     * @param   integer     $resource
     * @param   integer     $actions
     * @return	array
     */
    public function getJsActionsAllowed($profile, $resource, $actions)
    {
        $actionsAllowed = '[';
        $flag = false;
        foreach ($actions as $action)
        {
            if(parent::isAllowed($profile, $resource, $action->id_008))
            {
                if($flag) $actionsAllowed .= ',';
                $actionsAllowed .= '"' . $action->id_008 . '"';
                $flag =true;
            }
        }
        
        $actionsAllowed .= ']';
        return $actionsAllowed;    
    }

    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        try
        {
            return parent::isAllowed($role, $resource, $privilege);
        }
        catch(Exception\InvalidArgumentException $e)
        {
            return false;
        }
    }
}