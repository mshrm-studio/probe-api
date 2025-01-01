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

        // TOKEN CONTRACT

        $tokenContractAbiFileName = config('app.env') == 'production'
            ? 'nouns-token-contract-abi'
            : 'nouns-token-contract-abi-sepolia';

        $tokenContractAbi = Cache::remember($tokenContractAbiFileName, $seconds, function () use ($tokenContractAbiFileName) {
            $abiUrl = Storage::url('nouns/abi/'. $tokenContractAbiFileName .'.json');
            $response = Http::get($abiUrl);
            return $response->json();
        });

        $this->tokenContract = new Contract($web3->provider, json_encode($tokenContractAbi));
        $this->tokenContractAddress = config('services.nouns.contract.token_address');
        $this->tokenContract->at($this->tokenContractAddress);

        // AUCTION HOUSE CONTRACT

        $auctionHouseContractAbiFileName = config('app.env') == 'production'
            ? 'nouns-auction-house-contract-abi-v2'
            : 'nouns-auction-house-contract-abi-sepolia';

        $auctionHouseContractAbi = Cache::remember($auctionHouseContractAbiFileName, $seconds, function () use ($auctionHouseContractAbiFileName) {
            $abiUrl = Storage::url('nouns/abi/'. $auctionHouseContractAbiFileName .'.json');
            $response = Http::get($abiUrl);
            return $response->json();
        });

        $this->auctionHouseContract = new Contract($web3->provider, json_encode($auctionHouseContractAbi));
        $this->auctionHouseContractAddress = config('services.nouns.contract.auction_house_address');
        $this->auctionHouseContract->at($this->auctionHouseContractAddress);
    }
}
