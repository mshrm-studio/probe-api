<?php

namespace App\Services;

use App\Services\BaseNounsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Web3\Web3;
use Web3\Contract;

class LilNounsService extends BaseNounsService {
    public function __construct(Web3 $web3) {
        $this->web3 = $web3;

        $seconds = 300;

        $fileName = 'lil-nouns-contract-abi-v2';

        $abi = Cache::remember($fileName, $seconds, function () use ($fileName) {
            $abiUrl = Storage::url('lils/abi/'. $fileName .'.json');
            $response = Http::get($abiUrl);
            return $response->json();
        });

        $this->contract = new Contract($web3->provider, json_encode($abi));
        $this->contractAddress = config('services.lil_nouns.contract.token_address');
        $this->contract->at($this->contractAddress);
    }
}
