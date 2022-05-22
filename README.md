<h1 align="center">ClearLag</h1>
<p align="center">
A plugin for <a href="https://github.com/pmmp/PocketMine-MP">Pocketmine-MP</a>
</p>

---

## About
ClearLag is a plugin for Pocketmine-MP that removes entities after a certain period of time to prevent lags.

## Installation

- [Download](https://poggit.pmmp.io/p/ClearLag) ClearLag
- Move the plugin (`ClearLag.phar`) to the `plugins/` folder of your server
- Start the server, that's it!

## For Developers
You can use ClearLag in your plugins to remove entities.

You can either use the `ClearLag->doClear()` method, which follows the configuration of the ClearLag plugin:
```php
$result = \tobias14\clearlag\ClearLag::getInstance()->doClear();

/** @var int $clearedEntityCount Number of cleared entities */
$clearedEntityCount = $result['entity_count'];

/** @var int $clearedItemCount Number of cleared items */
$clearedItemCount = $result['item_count'];
```

Or you can use the `EntityRemover`, which offers a few more options and does not use the configuration of the ClearLag plugin:
```php
final class EntityRemover
{

    /**
     * @param array<class-string<Entity>>|null $entityTypes Entity types to be removed. If null, all will be removed.
     * @param string[] $exclusions Entity-/item-names which are spared.
     * @param World[]|null $worlds Affected worlds. If null all loaded worlds.
     * @return int Number of removed entities.
     */
    public static function removeEntities(?array $entityTypes = null, array $exclusions = [], ?array $worlds = null): int;

}
```

E.g. removal of all entities that inherit from `Zombie`, except `CustomZombie`(s).
```php
/** @var int $clearedEntityCount Number of cleared entities */
$clearedEntityCount = \tobias14\clearlag\utils\EntityRemover::removeEntities([\pocketmine\entity\Zombie::class], ['CustomZombie']);
```

## Contributing
You think you can do better or have discovered a bug? Then do it yourself and help the project.

Simply fork the repository and create a pull request.

## License
- GPL-3.0

<sub><sup>Icon by <a href="https://icons8.com/">Icons8</a></sup></sub>
