<?php

namespace App\Contracts;

interface NounsServiceContract {
    public function getTotalSupply(): int;
    public function getTokenByIndex(int $index): ?int;
    public function getTokenURI(int $tokenId): ?string;
    public function getSeeds(int $tokenId): array;
}
