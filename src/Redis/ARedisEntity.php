<?php
namespace LwtHelper\Redis;

use LwtHelper\Redis\ARedisConnection;
/**
 * A base class for redis entities.
 * Extends CBehavior to allow entities to be attached to models and components.
 * <pre>
 * $user = User::model()->findByPk(1);
 * $counter = new ARedisCounter("totalLogins");
 * $user->attachBehavior("totalLogins", $counter);
 * echo $user->totalLogins."\n"; // 0
 * $user->totalLogins->increment();
 * echo $user->totalLogins."\n"; // 1
 *
 * $friends = new ARedisSet("friendIds");
 * $user->attachBehavior("friendIds",$friends);
 * foreach($user->friendIds as $id) {
 *  echo "User ".$user->id." is friends with user ".$id."\n";
 * }
 *
 * </pre>
 * @package packages.redis
 * @author Charles Pick
 */
class ARedisEntity  extends ARedisConnection{
	/**
	 * The name of the redis entity (key)
	 * @var string
	 */
	public $name;

	/**
	 * Holds the redis connection
	 * @var ARedisConnection
	 */
	protected $_connection;

	/**
	 * The old name of this entity
	 * @var string
	 */
	protected $_oldName;
    protected $_client;
	/**
	 * Constructor
	 * @param string $name the name of the entity
	 * @param ARedisConnection|string $connection the redis connection to use with this entity
	 */
	public function __construct($name = null, $connection = null) {
		if ($name !== null) {
			$this->name = $name;
		}
	}
	/**
	 * Sets the expiration time in seconds to this entity 
	 *  @param integer number of expiration for this entity in seconds
	 */
	public function expire($seconds)
	{
		return $this->expire($this->name, $seconds);
	}
}