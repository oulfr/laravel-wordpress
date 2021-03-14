<?php

namespace App\Models;

/**
 * Core class used to implement a user roles API.
 *
 * The role option is simple, the structure is organized by role name that store
 * the name in value of the 'name' key. The capabilities are stored as an array
 * in the value of the 'capability' key.
 *
 *     array (
 *          'rolename' => array (
 *              'name' => 'rolename',
 *              'capabilities' => array()
 *          )
 *     )
 *
 *
 */
class Roles extends Option
{
    /**
     * List of roles and capabilities.
     *
     *
     * @var array[]
     */
    public $roles;

    /**
     * List of the role objects.
     *
     *
     * @var Role[]
     */
    public $role_objects = array();

    /**
     * List of role names.
     *
     *
     * @var string[]
     */
    public $role_names = array();

    /**
     * Option name for storing role list.
     *
     *
     * @var string
     */
    public $role_key;


    /**
     * Constructor
     * @global array $wp_user_roles Used to set the 'roles' property value.
     *
     * `$site_id` argument was added.
     *
     */
    public function __construct()
    {
        $this->role_key = 'wp_user_roles';
        if (!empty($this->roles)) {
            return;
        }
        $this->roles = $this->get_roles_data();
        $this->init_roles();
    }

    /**
     * Add role name with capabilities to list.
     *
     * Updates the list of roles, if the role doesn't already exist.
     *
     * The capabilities are defined in the following format `array( 'read' => true );`
     * To explicitly deny a role a capability you set the value for that capability to false.
     *
     *
     *
     * @param string $role Role name.
     * @param string $display_name Role display name.
     * @param bool[] $capabilities List of capabilities keyed by the capability name,
     *                             e.g. array( 'edit_posts' => true, 'delete_posts' => false ).
     * @return Role|void Role object, if role is added.
     */
    public function add_role($role, $display_name, $capabilities = array())
    {
        if (empty($role) || isset($this->roles[$role])) {
            return;
        }

        $this->roles[$role] = array(
            'name' => $display_name,
            'capabilities' => $capabilities,
        );
        static::addOrUpdate($this->role_key, $this->roles);
        $this->role_objects[$role] = new Role($role, $capabilities);
        $this->role_names[$role] = $display_name;
        return $this->role_objects[$role];
    }

    /**
     * Remove role by name.
     *
     *
     *
     * @param string $role Role name.
     */
    public function remove_role($role)
    {
        if (!isset($this->role_objects[$role])) {
            return;
        }

        unset($this->role_objects[$role]);
        unset($this->role_names[$role]);
        unset($this->roles[$role]);

        static::addOrUpdate($this->role_key, $this->roles);

        if (static::get('default_role') == $role) {
            static::addOrUpdate('default_role', 'subscriber');
        }
    }

    /**
     * Add capability to role.
     *
     *
     *
     * @param string $role Role name.
     * @param string $cap Capability name.
     * @param bool $grant Optional. Whether role is capable of performing capability.
     *                      Default true.
     */
    public function add_cap($role, $cap, $grant = true)
    {
        if (!isset($this->roles[$role])) {
            return;
        }

        $this->roles[$role]['capabilities'][$cap] = $grant;
        static::addOrUpdate($this->role_key, $this->roles);
    }

    /**
     * Remove capability from role.
     *
     *
     *
     * @param string $role Role name.
     * @param string $cap Capability name.
     */
    public function remove_cap($role, $cap)
    {
        if (!isset($this->roles[$role])) {
            return;
        }

        unset($this->roles[$role]['capabilities'][$cap]);
        static::addOrUpdate($this->role_key, $this->roles);

    }

    /**
     * Retrieve role object by name.
     *
     *
     *
     * @param string $role Role name.
     * @return Role|null Role object if found, null if the role does not exist.
     */
    public function get_role($role)
    {
        if (isset($this->role_objects[$role])) {
            return $this->role_objects[$role];
        } else {
            return null;
        }
    }

    /**
     * Retrieve list of role names.
     *
     *
     *
     * @return string[] List of role names.
     */
    public function get_names()
    {
        return $this->role_names;
    }

    /**
     * Whether role name is currently in the list of available roles.
     *
     *
     *
     * @param string $role Role name to look up.
     * @return bool
     */
    public function is_role($role)
    {
        return isset($this->role_names[$role]);
    }

    /**
     * Initializes all of the available roles.
     *
     */
    public function init_roles()
    {
        if (empty($this->roles)) {
            return;
        }

        $this->role_objects = array();
        $this->role_names = array();
        foreach (array_keys($this->roles) as $role) {
            $this->role_objects[$role] = new Role($role, $this->roles[$role]['capabilities']);
            $this->role_names[$role] = $this->roles[$role]['name'];
        }
    }

    /**
     * Gets the available roles data.
     *
     * @return array Roles array.
     * @global array $wp_user_roles Used to set the 'roles' property value.
     *
     *
     */
    protected function get_roles_data()
    {
        return maybe_unserialize(Option::get($this->role_key));
    }
}
