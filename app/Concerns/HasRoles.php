<?php

namespace App\Concerns;

use App\Models\Roles;

trait HasRoles
{
    /**
     * Capabilities that the individual user has been granted outside of those inherited from their role.
     * @var bool[] Array of key/value pairs where keys represent a capability name
     *             and boolean values represent whether the user has that capability.
     */
    protected $caps = array();

    /**
     * User metadata option name.
     * @var string
     */
    protected $cap_key = 'wp_capabilities';

    /**
     * The roles the user is part of.
     * @var string[]
     */
    protected $roles = array();

    /**
     * All capabilities the user has, including individual and role based.
     * @var bool[] Array of key/value pairs where keys represent a capability name
     *             and boolean values represent whether the user has that capability.
     */
    protected $allcaps = array();


    /**
     * When user is retrived init all roles and caps
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::retrieved(function ($user) {
            $user->init();
        });
    }

    /**
     * Init the user roles
     */
    protected function init(): void
    {
        $this->caps = $this->get_caps_data();
        $this->get_role_caps(app('roles'));
    }

    /**
     * @return array
     */
    protected function getRolesAttribute(): array
    {
        if (empty($this->roles)) {
            $this->init(app('roles'));
        }
        return $this->roles;
    }

    /**
     * @return array
     */
    protected function getCapsAttribute(): array
    {
        if (empty($this->caps)) {
            $this->init(app('roles'));
        }
        return $this->caps;
    }

    /**
     * @return array
     */
    protected function getAllcapsAttribute(): array
    {
        if (empty($this->allcaps)) {
            $this->init(app('roles'));
        }
        return $this->allcaps;
    }

    /**
     * Gets the available user capabilities data.
     *
     * @return array List of capabilities keyed by the capability name,
     *                e.g. array( 'edit_posts' => true, 'delete_posts' => false ).
     *
     */
    protected function get_caps_data(): array
    {
        $caps = $this->getMeta($this->cap_key);

        if (!is_array($caps)) {
            $caps = array();
        }
        $this->caps = $caps;
        return $this->caps;
    }

    /**
     * Retrieves all of the capabilities of the user's roles, and merges them with
     * individual user capabilities.
     *
     * All of the capabilities of the user's roles are merged with the user's individual
     * capabilities. This means that the user can be denied specific capabilities that
     * their role might have, but the user is specifically denied.
     *
     *
     * @param Roles $roles
     * @return array Array of key/value pairs where keys represent a capability name
     *                and boolean values represent whether the user has that capability.
     */
    protected function get_role_caps(Roles $roles): array
    {
        // Filter out caps that are not role names and assign to $this->roles.
        if (is_array($this->caps)) {
            $this->roles = array_filter(array_keys($this->caps), array($roles, 'is_role'));
        }

        // Build $allcaps from role caps, overlay user's $caps.
        $this->allcaps = array();
        foreach ((array)$this->roles as $role) {
            $the_role = $roles->get_role($role);
            $this->allcaps = array_merge((array)$this->allcaps, (array)$the_role->capabilities);
        }
        $this->allcaps = array_merge((array)$this->allcaps, (array)$this->caps);
        return $this->allcaps;
    }

    /**
     * Returns whether the user has the specified capability.
     *
     * This function also accepts an ID of an object to check against if the capability is a meta capability. Meta
     * capabilities such as `edit_post` and `edit_user` are capabilities used by the `map_meta_cap()` function to
     * map to primitive capabilities that a user or role has, such as `edit_posts` and `edit_others_posts`.
     *
     * Example usage:
     *
     *     $user->has_cap( 'edit_posts' );
     *
     * While checking against a role in place of a capability is supported in part, this practice is discouraged as it
     * may produce unreliable results.
     *
     * @param string $cap Capability name.
     * @return bool Whether the user has the given capability, or, if an object ID is passed, whether the user has
     *              the given capability for that object.
     * Formalized the existing and already documented `...$args` parameter
     *              by adding it to the function signature.
     *
     */
    public function has_cap($cap): bool
    {
        if (empty($this->allcaps) || empty($this->allcaps[$cap])) {
            return false;
        }

        return $this->allcaps[$cap];
    }

    /**
     * Determine if the model has any of the given cap(s).
     *
     * @param string|int|array| $roles
     *
     * @return bool
     */
    public function hasAnyCap($roles): bool
    {
        if (is_string($roles) && false !== strpos($roles, '|')) {
            $roles = $this->convertPipeToArray($roles);
        }
        if (is_string($roles)) {
            return $this->has_cap($roles);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->has_cap($role)) {
                    return true;
                }
            }
            return false;
        }

        return false;
    }

    /**
     * Determine if the model has all of the given role(s).
     *
     * @param string|array|$roles
     * @param string|null
     * @return bool
     */
    public function hasAllCaps($roles): bool
    {
        if (is_string($roles) && false !== strpos($roles, '|')) {
            $roles = $this->convertPipeToArray($roles);
        }

        if (is_string($roles)) {
            return $this->has_cap($roles);
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if (!$this->has_cap($role)) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Check if the User is an admin.
     *
     * @return boolean
     */
    public function isAdmin(): bool
    {
        return $this->has_cap('administrator');
    }

    /**
     * Check if the User is an editor.
     *
     * @return boolean
     */
    public function isEditor(): bool
    {
        return $this->has_cap('editor');
    }

    /**
     * Check if the User is an author.
     *
     * @return boolean
     */
    public function isAuthor(): bool
    {
        return $this->has_cap('author');
    }

    /**
     * Check if the User is a contributor.
     *
     * @return boolean
     */
    public function isContributor(): bool
    {
        return $this->has_cap('contributor');
    }

    /**
     * Check if the User is a subscriber.
     *
     * @return boolean
     */
    public function isSubscriber(): bool
    {
        return $this->has_cap('subscriber');
    }

    /**
     * @param string $pipeString
     * @return false|string|string[]
     */
    protected function convertPipeToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter) {
            return explode('|', $pipeString);
        }

        if (!in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }
}
