<?
#
# Simple class to hold user data
#
# @author Sergey Chernyshev
# @version $Rev: 20 $
#
# $Id: User.php 40 2008-02-27 06:04:50Z sergey $
#
class User 
{
	private $id;

	function __construct($id)
	{
		$this->id = $id;
	}

	function toString()
	{
		print $id;
	}

	# compare two users - this should be used instead of == or ===
	function equals($user)
	{
		return $user->toString() == $this->toString(); # simple implementation for now
	}
}
