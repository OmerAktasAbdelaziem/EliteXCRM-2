<?php

namespace App\Http\Services\Wallet;

use App\Http\Repositories\Wallet\Interfaces\WalletRepositoryInterface;
use App\Http\Services\Wallet\Interfaces\WalletServiceInterface;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletService implements WalletServiceInterface
{
    protected WalletRepositoryInterface $walletRepository;

    public function __construct(WalletRepositoryInterface $walletRepository) {
        $this->walletRepository = $walletRepository;
    }

    public function getAll(): Collection
    {
        $results = $this->walletRepository->getAll();
        return $results;
    }

    public function getById(int $id): Collection
    {
        $results = $this->walletRepository->getById($id);
        return $results;
    }

    public function getByFilters(array $params, array $with = []): Collection
    {
        $results = $this->walletRepository->getByFilters($params, $with);
        return $results;
    }

    public function create(array $data): Collection
    {
        $userAuth = Auth::user();
        
        $walletInputs = [
            'pipeline_id'   => $userAuth->pipeline_id,
            'type'          => $data['type'],
            'name'          => $data['name'],
            // 'address'       => $data['address'],
            // 'network'       => $data['network'],
        ];

        $wallet = DB::transaction(function () use ($walletInputs, $data) {
            $wallet = $this->walletRepository->create($walletInputs);
            $walletRecord = $wallet->first();
            $walletRecord->countries()->sync($data['countries']);

            $this->syncWalletFields($walletRecord, $data);

            return $wallet;
        });

        return $wallet;
    }

    public function update(int $id, array $data): int
    {
        $walletInputs = [
            'type'      => $data['type'],
            'name'      => $data['name'],
            // 'address'   => $data['address'],
            // 'network'   => $data['network'],
        ];
     
        $done = DB::transaction(function () use ($id, $walletInputs, $data) {

            $done =  $this->walletRepository->update($id, $walletInputs);

            if ($done) {
                $walletRecord = Wallet::findOrFail($id);
                
                $walletRecord->countries()->sync($data['countries']);

                $this->syncWalletFields($walletRecord, $data);
            }

            return $done;
        });

        return $done;
    }

    public function deleteByParams(array $params): int
    {
        return $this->walletRepository->deleteByParams($params);
    }


    /**
     * Helper logic to build out the custom field mappings synchronously with incremental indices.
     */
    private function syncWalletFields(Wallet $wallet, array $validated): void
    {
        // Wipe prior custom properties configurations to avoid duplication patterns
        $wallet->fields()->delete();

        $englishFields = $validated['english_field_names'] ?? [];
        $arabicFields  = $validated['arabic_field_names'] ?? [];
        $englishVal  = $validated['english_field_values'] ?? [];
        $arabicVal  = $validated['arabic_field_values'] ?? [];

        $preparedFields = [];
        foreach ($englishFields as $index => $englishName) {
            // Ignore accidental empty arrays row submissions
            if (empty($englishName) || empty($arabicFields[$index])) {
                continue;
            }

            $preparedFields[] = [
                'arabic_field_name'     => $arabicFields[$index],
                'english_field_name'    => $englishName,
                'english_field_value'  => $englishVal ? $englishVal[$index] : null,
                'arabic_field_value'   => $arabicVal ? $arabicVal[$index] : null,
                'order'                 => $index,
                'created_at'            => now(),
                'updated_at'            => now(),
            ];
        }

        if (!empty($preparedFields)) {
            $wallet->fields()->createMany($preparedFields);
        }
    }

}
