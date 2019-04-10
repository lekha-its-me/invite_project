<?php

namespace App\Repository;

use App\Service\ImportGuestListService;
use App\Service\ImportGuestListServiceInterface;

interface GuestRepositoryInterface
{
    public function findAllNotComes();

    public function findOneByHash(string $hash);

    public function addGuest(ImportGuestListServiceInterface $importGuestListService, string $filePath);
}
