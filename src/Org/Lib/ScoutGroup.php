<?php
/**
 * Contains ScoutGroup class
 * @author Alexander Krantz
 */
namespace Org\Lib;

/**
 * The whole scout group that is part of the scout organisation.
 */
class ScoutGroup {
    use InternalTrait;

    /** @var int The scout group id. */
    private $id;

    /** @var Member[] The members in the scout group indexed by their id. */
    private $members;

    /** @var Troop[] The troops of the scout group indexed by their id. */
    private $troopsIdIndexed;

    /** @var Troop[] The troops of the scout group indexed by their name. */
    private $troopsNameIndexed;

    /** @var Branch[] The branches of the scout group indexed by their id. */
    private $branchesIdIndexed;

    /** @var Branch[] The branches of the scout group indexed by their name. */
    private $branchesNameIndexed;

    /** @var RoleGroup[] The scout group roles indexed by their id. */
    private $roleGroupsIdIndexed;

    /** @var RoleGroup[] The scout group roles indexed by their name. */
    private $roleGroupsNameIndexed;

    /** @var CustomList[] The custom lists of the scout group indexed by their id. */
    private $customListsIdIndexed;

    /** @var CustomList[] The custom lists of the scout group indexed by their name. */
    private $customListsTitleIndexed;

    /** @var callable The function that gets the custom list until custom lists are phased out of here. */
    private $customListFetcher;

    /**
     * Creates a new ScoutGroup with the specified id.
     * @internal
     * @param int $id The scout group id.
     */
    public function __construct(int $id) {
        $this->id = $id;
        $this->members = new \ArrayObject();
        $this->troopsIdIndexed = new \ArrayObject();
        $this->troopsNameIndexed = new \ArrayObject();
        $this->branchesIdIndexed = new \ArrayObject();
        $this->branchesNameIndexed = new \ArrayObject();
        $this->roleGroupsIdIndexed = new \ArrayObject();
        $this->roleGroupsNameIndexed = new \ArrayObject();
        $this->customListsIdIndexed = new \ArrayObject();
        $this->customListsTitleIndexed = new \ArrayObject();
    }

    /**
     * Gets the scout group scoutnet id.
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Gets the list of members of the group indexed by scoutnet id.
     * @return Member[]
     */
    public function getMembers() {
        return $this->members->getArrayCopy();
    }

    /**
     * Adds a member.
     * @param Member $member
     * @return void
     */
    private function addMember(Member $member) {
        $this->members[$member->getId()] = $member;
    }

    /**
     * Gets the list of branches.
     * @param bool $idIndexed Wether to get list indexed by id or name.
     * @return Branch[]
     */
    public function getBranches(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->branchesIdIndexed->getArrayCopy();
        } else {
            return $this->branchesNameIndexed->getArrayCopy();
        }
    }

    /**
     * Adds a branch.
     * @param Branch $branch
     * @return void
     */
    private function addBranch(Branch $branch) {
        $this->branchesIdIndexed[$branch->getId()] = $branch;
        $this->branchesNameIndexed[$branch->getName()] = $branch;
    }

    /**
     * Gets the list of troops of the group.
     * @param bool $idIndexed Wether to get list indexed by id or name.
     * @return Troop[]
     */
    public function getTroops(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->troopsIdIndexed->getArrayCopy();
        } else {
            return $this->troopsNameIndexed->getArrayCopy();
        }
    }

    /**
     * Adds a troop.
     * @param Troop $troop
     * @return void
     */
    private function addTroop(Troop $troop) {
        $this->troopsIdIndexed[$troop->getId()] = $troop;
        $this->troopsNameIndexed[$troop->getName()] = $troop;
    }

    /**
     * Gets the list of role groups of the group.
     * @param bool $idIndexed Wether to get list indexed by id or name.
     * @return RoleGroup[]
     */
    public function getRoleGroups(bool $idIndexed = false) {
        if ($idIndexed) {
            return $this->roleGroupsIdIndexed->getArrayCopy();
        } else {
            return $this->roleGroupsNameIndexed->getArrayCopy();
        }
    }

    /** 
     * Adds a role group.
     * @param RoleGroup $roleGroup
     * @return void
     */
    private function addRoleGroup(RoleGroup $roleGroup) {
        $this->roleGroupsIdIndexed[$roleGroup->getId()] = $roleGroup;
        $this->roleGroupsNameIndexed[$roleGroup->getRoleName()] = $roleGroup;
    }

    /**
     * Gets the list of custom lists of the group.
     * @param bool $idIndexed Wether to get the list indexed by id or title.
     * @return CustomList[]
     * @deprecated 1.2.1 Custom lists are moved to the the ScoutOrg class.
     */
    public function getCustomLists(bool $idIndexed = false) {
        $customListFetcher = $this->customListFetcher;
        return $customListFetcher($idIndexed);
    }

    /**
     * Adds a custom list.
     * @param CustomList $customList
     * @return void
     * @deprecated 1.2.1 Custom lists are moved to the the ScoutOrg class.
     */
    private function addCustomList(CustomList $customList) {
        $this->customListsIdIndexed[$customList->getId()] = $customList;
        $this->customListsTitleIndexed[$customList->getTitle()] = $customList;
    }
}