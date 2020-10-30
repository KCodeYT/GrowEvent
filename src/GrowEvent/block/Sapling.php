<?php

namespace GrowEvent\block;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\level\generator\object\Tree;
use pocketmine\utils\Random;
use GrowEvent\level\ArrayChunkManager;
use GrowEvent\event\StructureGrowEvent;

class Sapling extends \pocketmine\block\Sapling {

    public function __construct(int $meta = 0) {
        parent::__construct($meta);
    }

    public function onActivate(Item $item, Player $player = null): bool {
        if($item->getId() === Item::DYE and $item->getDamage() === 0x0F){ //Bonemeal
            $chunkManager = new ArrayChunkManager($this->getLevelNonNull());
            Tree::growTree($chunkManager, $this->x, $this->y, $this->z, new Random(mt_rand()), $this->getVariant());
            $ev = new StructureGrowEvent($player, $this, $chunkManager->getBlocks(), true);
            $ev->call();
            if($ev->isCancelled())
                return false;
            foreach($ev->getBlocks() as $block)
                $this->getLevelNonNull()->setBlock($block, $block);
            $item->pop();
            return true;
        }

        return false;
    }

    public function onRandomTick(): void {
        if($this->level->getFullLightAt($this->x, $this->y, $this->z) >= 8 and mt_rand(1, 7) === 1){
            if(($this->meta & 0x08) === 0x08){
                $chunkManager = new ArrayChunkManager($this->getLevelNonNull());
                Tree::growTree($chunkManager, $this->x, $this->y, $this->z, new Random(mt_rand()), $this->getVariant());
                $ev = new StructureGrowEvent(null, $this, $chunkManager->getBlocks(), false);
                $ev->call();
                if($ev->isCancelled())
                    return;
                foreach($ev->getBlocks() as $block)
                    $this->getLevelNonNull()->setBlock($block, $block);
            }else{
                $this->meta |= 0x08;
                $this->getLevelNonNull()->setBlock($this, $this, true);
            }
        }
    }

}