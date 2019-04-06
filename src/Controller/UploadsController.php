<?php

namespace App\Controller;

use App\Exceptions\FileUploadException;
use App\Repository\GuestRepository;
use App\Service\FileUploadService;
use App\Service\ReadAndSaveDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UploadsController extends AbstractController
{
    public function index()
    {
        return $this->render('/uploads/index.html.twig');
    }

    public function getFile(ReadAndSaveDataService $readAndSaveDataService, GuestRepository $guestRepository)
    {
        $uploader = new FileUploadService();
        try {
            $file = $uploader->upload($_FILES);
        }
        catch (FileUploadException $exception)
        {
            return new Response('Ошибка при загрузке файла', 400);
        }

        $response = $guestRepository->addGuest($readAndSaveDataService, $file);

        return new Response($response, 200);
    }
}
