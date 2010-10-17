all:	.svn .git updatedb
	cd users && $(MAKE)

# if we don't have .git folder, let's assume we use SVN export
.git:
	# let's get DBUpgade 
	rm -rf dbupgrade
	svn update
	mkdir dbupgrade/
	svn export http://svn.github.com/sergeychernyshev/DBUpgrade.git _dbupgrade
	mv _dbupgrade/* dbupgrade/
	rm -rf _dbupgrade
	# now, let's get UserBase
	rm -rf users 
	svn update
	mkdir users/
	svn export http://svn.github.com/sergeychernyshev/UserBase.git _users
	mv _users/* users/
	rm -rf _users

# and vice versa (below is for git)
.svn:
	git pull origin master
	git submodule init
	git submodule update

updatedb:
	php dbupgrade.php

test:
	phpunit tests

testcoverage:
	php -d zend_extension="/usr/lib/php/modules/xdebug.so" /usr/local/bin/phpunit --coverage-html code_coverage tests
