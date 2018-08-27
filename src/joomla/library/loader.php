<?php
/**
 * Contains ScoutOrgLoader class
 * @author Alexander Krantz
 */

defined('_JEXEC') or die('Restricted access');

JLoader::registerNamespace('Org', __DIR__);

class ScoutOrgLoader {
    /** @var \Org\Lib\ScoutOrg Scout organisation instance */
    private static $scoutOrg;
    
    /**
     * Loads the scout org instance according to
     * the scoutorg component params and configs.
     * @return \Org\Lib\ScoutOrg|false
     */
    public static function load() {
        if (self::$scoutOrg) {
            return self::$scoutOrg;
        }
        
        $params = self::getParams();

        $domain = $params->get('domain', 'www.scoutnet.se');
        $groupId = $params->get('groupId', -1);
        $memberListKey = $params->get('memberListApiKey', '');
        $customListsKey = $params->get('customListsApiKey', '');
        $scoutnetCacheLifeTime = $params->get('scoutnetCacheLifeTime');

        $error = false;
        if ($groupId == -1) {
            $error = true;
        }
        if ($memberListKey == '' || $customListsKey == '') {
            $error = true;
        }
        if ($error) {
            return false;
        }

        $groupConfig = new \Org\Scoutnet\ScoutGroupConfig($groupId,
            $memberListKey, $customListsKey);
        $scoutnetConnection = new \Org\Scoutnet\ScoutnetConnection($groupConfig, $domain, $scoutnetCacheLifeTime);
        $scoutnetController = new \Org\Scoutnet\ScoutnetController($scoutnetConnection);

        $branchConfigs = self::getBranchConfigs();
        $scoutGroupfactory = new \Org\Scoutnet\ScoutGroupFactory($scoutnetController, $branchConfigs);
        $waitingListFactory = new \Org\Scoutnet\WaitingListFactory($scoutnetController);

        self::$scoutOrg = new \Org\Lib\ScoutOrg($scoutGroupfactory, $waitingListFactory);

        return self::$scoutOrg;
    }

    /**
     * Gets the component params.
     * @return JRegistry
     */
    private static function getParams() {
        return JComponentHelper::getParams('com_scoutorg');
    }

    /**
     * Gets the branch configs from the database.
     * @return \Org\Scoutnet\BranchConfig[]
     */
    private static function getBranchConfigs() {
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);
        $query->select('id, name')->from($db->quoteName('#__scoutorg_branches'));
        $db->setQuery($query);

        $configs = array();
        foreach ($db->loadObjectList() as $branchRow) {
            $query = $db->getQuery(true);
            $query->select('troop')
                ->from($db->quoteName('#__scoutorg_troops'))
                ->where("branch = {$branchRow->id}");
            $db->setQuery($query);

            $troops = array();
            foreach ($db->loadObjectList() as $troopRow) {
                $troops[] = $troopRow->troop;
            }
            $configs[] = new Org\Scoutnet\BranchConfig($branchRow->id, $branchRow->name, $troops);
        }

        return $configs;
    }
}