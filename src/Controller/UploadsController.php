<?php

namespace App\Controller;

use App\Exceptions\FileUploadException;
use App\Service\FileUploadService;
use App\Service\FileUploadsInterface;
use App\Service\ReadAndSaveDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UploadsController extends AbstractController
{
    public function index()
    {
        return $this->render('/uploads/index.html.twig');
    }

    public function getFile(FileUploadsInterface $fileUploads, ReadAndSaveDataService $reader)
    {
        $uploader = new FileUploadService();
        try {
            $uploader->upload($_FILES);
        }
        catch (FileUploadException $exception)
        {
            return new Response('Ошибка при загрузке файла', 400);
        }



        return new Response('Файл загружен', 200);
    }
}
