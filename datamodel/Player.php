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
require_once(dirname(__FILE__).'/Game.php');

class Player
{
	private $user;

	// if null, it's a dummy user with no DB representation
	private $player_id;

	public function __construct($user = null, $player_id = null)
	{
		$this->user = $user;
		$this->player_id = $player_id;
	}

	public function getID() {
		return $this->player_id;
	}

	public function asString()
	{
		return $this->getUser()->getName();
	}

	public function compare($player)
	{
		if ($this->getUser()->isTheSameAs($player->getUser()))
		{
			return true;
		}

		return false;
	}

	public function getUser() {
		global $db;

		if (is_null($this->user)) {
			if (is_null($this->player_id)) {
				throw new Exception("Can't get a user for dummy player");
			}

			$player_id = $this->player_id;
			$user_id = null;

			if ($stmt = $db->prepare('SELECT user_id FROM player WHERE player_id = ?'))
			{
				if (!$stmt->bind_param('i', $player_id))
				{
					 throw new Exception("Can't bind parameter".$stmt->error);
				}
				if (!$stmt->execute())
				{
					throw new Exception("Can't execute statement: ".$stmt->error);
				}
				if (!$stmt->bind_result($user_id))
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

			if (is_null($user_id)) {
				throw new Exception("Can't find player's user");
			}

			$this->user = User::getUser($user_id);
		}

		return $this->user;
	}

	public static function getPlayer($user) {
		global $db;

		$daily_activity = array();

		$user_id = $user->getID();

		if ($stmt = $db->prepare('SELECT player_id FROM player WHERE user_id = ?'))
		{
			if (!$stmt->bind_param('i', $user_id))
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

	public function getGames() {
		if (is_null($this->player_id)) {
			throw new Exception("Can't use dummy user to connect to a database");
		}

		return Game::getPlayerGames($this);
	}
}
