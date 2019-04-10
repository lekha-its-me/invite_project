<?php

namespace App\Repository;

use App\Service\ImportGuestListService;

interface GuestRepositoryInterface
{
    public function findAllNotComes();

    public function findOneByHash(string $hash);

    public function addGuest(ImportGuestListService $readAndSaveDataService, string $filePath);
}
