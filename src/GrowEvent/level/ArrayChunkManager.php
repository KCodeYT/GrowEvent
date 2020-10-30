<?php

namespace GrowEvent\level;

use pocketmine\level\ChunkManager;
use pocketmine\level\format\Chunk;
use pocketmine\block\Block;
use pocketmine\level\Position;

class ArrayChunkManager implements ChunkManager {

    /** @var ChunkManager $parent */
    private $parent;

    /** @var Block[] $blocks */
    private $blocks;

    public function __construct(ChunkManager $parent) {
        $this->parent = $parent;
        $this->blocks = [];
    }

    private function optionalBlock(int $x, int $y, int $z): ?Block {
        return empty($blocks = array_filter($this->blocks, function($block) use ($x, $y, $z) {
            return $block instanceof Block && $block->getFloorX() == $x && $block->getFloorY() == $y && $block->getFloorZ() == $z;
        })) ? null : array_values($blocks)[0];
    }

    public function getBlockIdAt(int $x, int $y, int $z): int {
        $optionalBlock = $this->optionalBlock($x, $y, $z);
        return array_map(function($block) {
            /** @var Block $block */
            return is_int($block) ? $block : $block->getId();
        }, $optionalBlock === null ? [$this->parent->getBlockIdAt($x, $y, $z)] : [$optionalBlock])[0];
    }

    public function setBlockIdAt(int $x, int $y, int $z, int $id) {
        $data = $this->getBlockDataAt($x, $y, $z);
        $optionalBlock = $this->optionalBlock($x, $y, $z);
        if($optionalBlock != null) unset($this->blocks[array_search($optionalBlock, $this->blocks)]);
        $this->blocks[] = Block::get($id, $data, new Position($x, $y, $z));
    }

    public function getBlockDataAt(int $x, int $y, int $z): int {
        $optionalBlock = $this->optionalBlock($x, $y, $z);
        return array_map(function($block) {
            /** @var Block $block */
            return is_int($block) ? $block : $block->getDamage();
        }, $optionalBlock === null ? [$this->parent->getBlockDataAt($x, $y, $z)] : [$optionalBlock])[0];
    }

    public function setBlockDataAt(int $x, int $y, int $z, int $data) {
        $id = $this->getBlockIdAt($x, $y, $z);
        $optionalBlock = $this->optionalBlock($x, $y, $z);
        if($optionalBlock != null) unset($this->blocks[array_search($optionalBlock, $this->blocks)]);
        $this->blocks[] = Block::get($id, $data, new Position($x, $y, $z));
    }

    public function getBlockLightAt(int $x, int $y, int $z): int {
        return $this->parent->getBlockLightAt($x, $y, $z);
    }

    public function setBlockLightAt(int $x, int $y, int $z, int $level) {
        $this->parent->setBlockLightAt($x, $y, $z, $level);
    }

    public function getBlockSkyLightAt(int $x, int $y, int $z): int {
        return $this->parent->getBlockSkyLightAt($x, $y, $z);
    }

    public function setBlockSkyLightAt(int $x, int $y, int $z, int $level) {
        $this->parent->setBlockSkyLightAt($x, $y, $z, $level);
    }

    public function getChunk(int $chunkX, int $chunkZ) {
        return $this->parent->getChunk($chunkX, $chunkZ);
    }

    public function setChunk(int $chunkX, int $chunkZ, Chunk $chunk = null) {
        $this->parent->setChunk($chunkX, $chunkZ, $chunk);
    }

    public function getSeed(): int {
        return $this->parent->getSeed();
    }

    public function getWorldHeight(): int {
        return $this->parent->getWorldHeight();
    }

    public function isInWorld(int $x, int $y, int $z): bool {
        return $this->parent->isInWorld($x, $y, $z);
    }

    /**
     * @return Block[]
     */
    public function getBlocks(): array {
        return $this->blocks;
    }

}