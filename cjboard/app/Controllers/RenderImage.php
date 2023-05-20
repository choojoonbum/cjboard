<?php

namespace App\Controllers;

class RenderImage extends BaseController
{
    public function index()
    {
        $imgPath = WRITEPATH.'/uploads/'.implode('/',func_get_args());
        $image = @file_get_contents($imgPath);
        $mimeType = @mime_content_type($imgPath);
        $this->response->setStatusCode(200)->setContentType($mimeType)->setBody($image)->send();
        exit();
    }

}
