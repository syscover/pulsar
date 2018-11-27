<?php namespace Syscover\Pulsar\Libraries;

use Syscover\Pulsar\Models\Resource;
use Syscover\Pulsar\Models\Permission;
use Zend\Permissions\Acl\Exception;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as AclResource;

class AclLibrary extends Acl
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
        $acl            = new AclLibrary();
        // get all resources in application
        $resources      = Resource::all();
        // get all permissions fron this profile
        $permissions    = Permission::getRecord($profile);

        // set profile id
        $acl->addRole(new Role($profile));

        // add resources to acl element
        foreach($resources as $resource)
            $acl->addResource(new AclResource($resource->id_007));

        // add resources to acl element
        foreach($permissions as $permission)
            $acl->allow($profile, $permission->resource_id_009, $permission->action_id_009);

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

    /**
     * Function to check permission
     *
     * @param null $resource
     * @param null $privilege
     * @param null $profile
     * @return bool
     */
    public function allows($resource = null, $privilege = null, $profile = null)
    {
        if($profile === null)
            $profile = auth()->guard('pulsar')->user()->profile_id_010;

        try
        {
            return parent::isAllowed($profile, $resource, $privilege);
        }
        catch(Exception\InvalidArgumentException $e)
        {
            return false;
        }
    }
}