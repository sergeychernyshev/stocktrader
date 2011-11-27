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

	// if null, it's a dummy user with no DB representation
	private $player_id;

	public function __construct($user, $player_id = null)
	{
		$this->user = $user;
		$this->player_id = $player_id;
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

	public static function getPlayer($user) {
		$db = UserConfig::getDB();

		$daily_activity = array();

		if ($stmt = $db->prepare('SELECT player_id FROM player WHERE user_id = ?'))
		{
			if (!$stmt->bind_param('i', $user->getID()))
			{
				 throw new Exception("Can't bind parameter".$stmt->error);
			}
			if (!$stmt->execute())
			{
				throw new Exception("Can't execute statement: ".$stmt->error);
			}
			if (!$stmt->bind_result($player_id))
			{
				throw new Exception("Can't bind result: ".$stmt->error);
			}

			$stmt->fetch();
			$stmt->close();
		}
		else
		{
			throw new Exception("Can't prepare statement: ".$db->error);
		}

		return is_null($player_id) ? null : new Player($user, $player_id);
	}
}
