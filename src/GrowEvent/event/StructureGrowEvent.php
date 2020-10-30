<?php

namespace GrowEvent\event;

use pocketmine\event\Cancellable;
use pocketmine\Player;
use pocketmine\event\Event;
use pocketmine\block\Block;

class StructureGrowEvent extends Event implements Cancellable {

    /** @var Player|null $player */
    protected $player;

    /** @var Block $block */
    protected $block;

    /** @var Block[] $blocks */
    protected $blocks;

    /** @var boolean $boneMeal */
    protected $boneMeal;

    public function __construct(?Player $player, Block $block, array $blocks, bool $boneMeal) {
        $this->player = $player;
        $this->block = $block;
        $this->blocks = $blocks;
        $this->boneMeal = $boneMeal;
    }

    /**
     * @return Player|null Player
     */
    public function getPlayer(): ?Player {
        return $this->player;
    }

    /**
     * @return Block
     */
    public function getBlock(): Block {
        return $this->block;
    }

    /**
     * @return Block[]
     */
    public function getBlocks(): array {
        return $this->blocks;
    }

    /**
     * @return bool
     */
    public function isBoneMeal(): bool {
        return $this->boneMeal;
    }

}