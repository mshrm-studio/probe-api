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

        $tokenContractAbiFileName = 'lil-nouns-contract-abi-v2';

        $tokenContractAbi = Cache::remember($tokenContractAbiFileName, $seconds, function () use ($tokenContractAbiFileName) {
            $abiUrl = Storage::url('lils/abi/'. $tokenContractAbiFileName .'.json');
            $response = Http::get($abiUrl);
            return $response->json();
        });

        $this->tokenContract = new Contract($web3->provider, json_encode($tokenContractAbi));
        $this->tokenContractAddress = config('services.lil_nouns.contract.token_address');
        $this->tokenContract->at($this->tokenContractAddress);
    }
}
