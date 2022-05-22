<?php
declare(strict_types=1);

namespace tobias14\clearlag\utils;

use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\object\ItemEntity;
use pocketmine\Server;
use pocketmine\world\World;
use ReflectionClass;

final class EntityRemover
{

    /**
     * @param array<class-string<Entity>>|null $entityTypes Entity types to be removed. If null, all will be removed.
     * @param string[] $exclusions Entity-/item-names which are spared.
     * @param World[]|null $worlds Affected worlds. If null all loaded worlds.
     * @return int Number of removed entities.
     */
    public static function removeEntities(?array $entityTypes = null, array $exclusions = [], ?array $worlds = null): int
    {
        $counter = 0;

        $exclusions = array_map(function(string $exclusion) {
            return strtolower(str_replace(' ', '', $exclusion));
        }, $exclusions);
        $worlds = $worlds ?? Server::getInstance()->getWorldManager()->getWorlds();

        foreach($worlds as $world) {
            foreach($world->getEntities() as $entity) {
                if($entityTypes !== null && !ArrayUtils::containsRelatedClassOf($entity, $entityTypes)) {
                    continue;
                }
                $name = method_exists($entity, 'getName') ? $entity->getName() : (new ReflectionClass($entity))->getShortName();
                $ltName = strtolower(str_replace(' ', '', $name));
                $isItemExclusion = $entity instanceof ItemEntity && in_array(strtolower(str_replace(' ', '', $entity->getItem()->getName())), $exclusions);
                if(in_array($ltName, $exclusions) || ($entity instanceof Human) || $isItemExclusion) {
                    continue;
                }
                $entity->flagForDespawn();
                $counter++;
            }
        }

        return $counter;
    }

}
