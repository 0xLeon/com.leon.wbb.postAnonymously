<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

/**
 * Removes user specific data from posts and shows corresponding option
 * 
 * @author	Stefan Hahn
 * @copyright	2012 Stefan Hahn
 * @license	Simplified BSD License License <http://projects.swallow-all-lies.com/licenses/simplified-bsd-license.txt>
 * @package	com.leon.wbb.postAnonymously
 * @subpackage	system.event.listener
 * @category 	Burning Board
 */
class ThreadAddFormPostAnonymouslyListener implements EventListener {
	protected static $userID = 0;
	protected static $ipAddress = '';
	
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if ($eventObj->board->getPermission('canPostAnonymously')) {
			if ($eventName === 'show') {
				WCF::getTPL()->append('additionalSettings', WCF::getTPL()->fetch('messageFormSettingsPostAnonymously'));
			}
			else if ($eventName === 'save') {
				if (isset($_POST['postAnonymously']) && intval($_POST['postAnonymously'])) {
					self::$userID = WCF::getUser()->userID;
					self::$ipAddress = WCF::getSession()->ipAddress;
					
					$eventObj->username = WCF::getLanguage()->get('wbb.threadAdd.anonymousUsername');
					WCF::getUser()->userID = 0;
					WCF::getSession()->ipAddress = '';
				}
			}
			else if ($eventName === 'saved') {
				WCF::getUser()->userID = self::$userID;
				WCF::getSession()->ipAddress = self::$ipAddress;
			}
		}
	}
}
