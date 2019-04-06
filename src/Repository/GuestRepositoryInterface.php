<?php

namespace App\Repository;

use App\Service\ReadAndSaveDataService;

interface GuestRepositoryInterface
{
    public function findAllNotComes();

    public function findOneByHash(string $hash);

    public function addGuest(ReadAndSaveDataService $readAndSaveDataService, string $filePath);
}
