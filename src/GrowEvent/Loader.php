<?php

namespace GrowEvent;

use pocketmine\plugin\PluginBase;

use pocketmine\block\BlockFactory;
use GrowEvent\block\Sapling;

class Loader extends PluginBase {

    public function onLoad() {
        BlockFactory::registerBlock(new Sapling(), true);
    }

}