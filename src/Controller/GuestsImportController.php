<?php

namespace App\Controller;

use App\Exceptions\FileUploadException;
use App\Repository\GuestRepository;
use App\Service\FileUploadService;
use App\Service\ImportGuestListService;
use App\Service\ImportGuestListServiceInterface;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class GuestsImportController extends AbstractController
{
    /**
     * @return Response
     */
    public function index()
    {
        return $this->render('/uploads/index.html.twig');
    }

    /**
     * @param ImportGuestListServiceInterface $importGuestListService
     * @param GuestRepository $guestRepository
     * @return bool|Response
     */
    public function getFile(ImportGuestListServiceInterface $importGuestListService, GuestRepository $guestRepository)
    {
        $uploader = new FileUploadService();
        try {
            $file = $uploader->upload($_FILES);
        }
        catch (FileUploadException $exception)
        {
            return new Response('Ошибка при загрузке файла', 400);
        }

        if($response = $guestRepository->addGuest($importGuestListService, $file))
        {
            return new Response('true', 200);
        }

        return false;

    }
}
