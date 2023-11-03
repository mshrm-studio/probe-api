<?php

namespace App\Contracts;

interface ERC721ServiceContract {
    public function getMintEvent(int $tokenId): array;
    public function getBlock(string $blockNumber): array;
}
