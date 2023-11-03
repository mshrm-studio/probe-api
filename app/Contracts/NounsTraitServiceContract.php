<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface NounsTraitServiceContract {
    public function getItems(): Collection;
}
