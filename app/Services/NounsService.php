<?php

namespace App\Services;

use App\Services\BaseNounsService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Web3\Web3;
use Web3\Contract;

class NounsService extends BaseNounsService {
    public function __construct(Web3 $web3) {
        $this->web3 = $web3;

        $seconds = 300;

        $fileName = config('app.env') == 'production'
            ? 'nouns-contract-abi-v2'
            : 'nouns-contract-abi-sepolia';

        $abi = Cache::remember($fileName, $seconds, function () use ($fileName) {
            $abiUrl = Storage::url('nouns/abi/'. $fileName .'.json');
            $response = Http::get($abiUrl);
            return $response->json();
        });

        $this->contract = new Contract($web3->provider, json_encode($abi));
        $this->contractAddress = config('services.nouns_contract.address');
        $this->contract->at($this->contractAddress);
    }
}
