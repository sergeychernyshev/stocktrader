<?
#
# This class represents game player
#
# @author Sergey Chernyshev
# @version $Rev: 63 $
#
# $Id: Player.php 2 2008-02-11 02:52:08Z sergey $
#
class Player
{
	private $username;
	private $name;
	private $id;

	function __construct($name, $username, $id)
	{
		$this->username = $username;
		$this->name = $name;
		$this->id = $id;
	}

	function asString()
	{
		return $this->name ? $this->name : $this->username;
	}

	function compare($user)
	{
		if ($this->id == $user->id)
		{
			return true;
		}

		return false;
	}
}
