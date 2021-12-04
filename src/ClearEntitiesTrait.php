<?php
declare(strict_types=1);

namespace tobias14\clearlag;

use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\ItemEntity;

trait ClearEntitiesTrait
{

    /**
     * @param string[] $exceptions
     */
    protected function clearItems(array $exceptions): int
    {
        $count = 0;
        $exceptions = array_map('strtolower', $exceptions);
        $worlds = ClearLag::getInstance()->getServer()->getWorldManager()->getWorlds();
        foreach($worlds as $world) {
            foreach($world->getEntities() as $entity) {
                if($entity instanceof ItemEntity) {
                    if(in_array(strtolower($entity->getItem()->getName()), $exceptions)) {
                        continue;
                    }
                    $entity->flagForDespawn();
                    $count++;
                }
            }
        }
        return $count;
    }

    /**
     * @param string[] $exceptions
     */
    protected function clearEntities(array $exceptions): int
    {
        $count = 0;
        $exceptions = array_map('strtolower', $exceptions);
        $worlds = ClearLag::getInstance()->getServer()->getWorldManager()->getWorlds();
        foreach($worlds as $world) {
            foreach($world->getEntities() as $entity) {
                if(($entity instanceof Human) or !($entity instanceof Living) and !($entity instanceof ExperienceOrb)) {
                    continue;
                }
                $eName = $entity instanceof ExperienceOrb ? 'ExperienceOrb' : $entity->getName();
                if(in_array(strtolower($eName), $exceptions)) {
                    continue;
                }
                $entity->flagForDespawn();
                $count++;
            }
        }
        return $count;
    }

}
