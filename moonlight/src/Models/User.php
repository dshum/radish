<?php namespace Moonlight\Models;

use Illuminate\Database\Eloquent\Model;
use Moonlight\Main\ElementInterface;

class User extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'admin_users';

	/**
	 * The Eloquent group model.
	 *
	 * @var string
	 */
	protected static $groupModel = 'Moonlight\Models\Group';

	/**
	 * The user groups pivot table name.
	 *
	 * @var string
	 */
	protected static $userGroupsPivot = 'admin_users_groups_pivot';

	/**
	 * The user groups.
	 *
	 * @var array
	 */
	protected $userGroups;

    public function getDates()
	{
		return array('created_at', 'updated_at', 'last_login');
	}

	public static function boot()
	{
		parent::boot();

		static::created(function($element) {
			$element->flush();
		});

		static::saved(function($element) {
			$element->flush();
		});

		static::deleted(function($element) {
			$element->flush();
		});
  }

	public function flush()
	{
		\Cache::tags('User', 'Group')->flush();

		\Cache::forget("getUserById({$this->id})");
	}

	public function isSuperUser()
	{
		return $this->superuser ? true : false;
	}

	public function groups()
	{
		return $this->belongsToMany(static::$groupModel, static::$userGroupsPivot);
	}

	public function addGroup(Group $group)
	{
		if ( ! $this->inGroup($group)) {
			$this->groups()->attach($group);
		}
	}

	public function removeGroup(Group $group)
	{
		if ($this->inGroup($group)) {
			$this->groups()->detach($group);
			$this->invalidateUserGroupsCache();
		}

		return true;
	}

	public function inGroup(Group $group)
	{
		foreach ($this->getGroups() as $_group) {
			if ($_group->id == $group->id) {
				return true;
			}
		}

		return false;
	}

	public function getGroups()
	{
    return $this->groups()->get();
	}

	public function invalidateUserGroupsCache()
    {
		$this->userGroups = null;
    }

	public function getUnserializedParameters()
	{
		try {
			return unserialize($this->parameters);
		} catch (\Exception $e) {}

		return null;
	}

	public function getParameter($name)
	{
		$unserializedParameters = $this->getUnserializedParameters();

		return
			isset($unserializedParameters[$name])
			? $unserializedParameters[$name]
			: null;
	}

	public function setParameter($name, $value)
	{
		try {

			$unserializedParameters = $this->getUnserializedParameters();

			$unserializedParameters[$name] = $value;

			$parameters = serialize($unserializedParameters);

			$this->parameters = $parameters;

			$this->save();

		} catch (\Exception $e) {}

		return $this;
	}

	public function hasAccess($name)
	{
		if ($this->isSuperUser()) return true;

		$groups = $this->getGroups();

		foreach ($groups as $group) {
			if ($group->hasAccess($name)) {
				return true;
			}
		}

		return false;
	}

	public function hasUpdateDefaultAccess(Item $item)
	{
		if ($this->isSuperUser()) return true;

		$groups = $this->getGroups();

		foreach ($groups as $group) {
			$access = $group->getItemAccess($item);
			if (in_array($access, array('update', 'delete'))) {
				return true;
			}
		}

		return false;
	}

	public function hasViewAccess(ElementInterface $element)
	{
		if ($this->isSuperUser()) return true;

		$groups = $this->getGroups();

		foreach ($groups as $group) {
			$access = $group->getElementAccess($element);
			if (in_array($access, array('view', 'update', 'delete'))) {
				return true;
			}
		}

		return false;
	}

	public function hasUpdateAccess(ElementInterface $element)
	{
		if ($this->isSuperUser()) return true;

		$groups = $this->getGroups();

		foreach ($groups as $group) {
			$access = $group->getElementAccess($element);
			if (in_array($access, array('update', 'delete'))) {
				return true;
			}
		}

		return false;
	}

	public function hasDeleteAccess(ElementInterface $element)
	{
		if ($this->isSuperUser()) return true;

		$groups = $this->getGroups();

		foreach ($groups as $group) {
			$access = $group->getElementAccess($element);
			if (in_array($access, array('delete'))) {
				return true;
			}
		}

		return false;
	}
}
