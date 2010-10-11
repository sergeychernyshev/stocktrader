<?
#
# This class represents game player
#
# @author Sergey Chernyshev
# @version $Rev: 63 $
#
# $Id: Player.php 2 2008-02-11 02:52:08Z sergey $
#

require_once(dirname(dirname(__FILE__)).'/users/users.php');
class Player
{
	private $user;

	public function __construct($id)
	{
		$this->user = User::getUser($id);
	}

	public function asString()
	{
		return $this->user->getName();
	}

	public function compare($player)
	{
		if ($this->user->isTheSameAs($player->getUser()))
		{
			return true;
		}

		return false;
	}

	public function getUser() {
		return $this->user;
	}
}
