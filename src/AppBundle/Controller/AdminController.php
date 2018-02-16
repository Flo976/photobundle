<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Document;
use Gumlet\ImageResize;

class AdminController extends Controller
{
    
     /**
     *
     * @Method({"GET", "POST"})
     * @Route("/ajax/snippet/image/send", name="ajax_snippet_image_send")
     */
    public function ajaxSnippetImageSendAction(Request $request)
    {
        $em = $this->container->get("doctrine.orm.default_entity_manager");

        $document = new Document();
        $media = $request->files->get('file');
        $nom = $media->getClientOriginalName();
        $path = "/uploads/documents/$nom";
        $path_dir = "uploads/documents/$nom";
       

        $document->setFile($media);
        $document->setPath($path);        
        $document->setName($media->getClientOriginalName());
        $document->upload();       
        $em->persist($document);
        $em->flush();





        $image = new ImageResize($path_dir);
        $imglarge = new ImageResize($path_dir);


         $imglarge->addFilter(function ($imageDesc) {
          });
        // Add banner on bottom left corner
        $image18Plus = 'img/test.png';
        $imglarge->addFilter(function ($imageDesc) use ($image18Plus) {
            $logo = imagecreatefrompng($image18Plus);
            $logo_width = imagesx($logo);
            $logo_height = imagesy($logo);
            $image_width = imagesx($imageDesc);
            $image_height = imagesy($imageDesc);
            $image_x = $image_width - $logo_width - 10;
            $image_y = $image_height - $logo_height - 10;
            imagecopy($imageDesc, $logo, $image_x, $image_y, 0, 0, $logo_width, $logo_height);
        });

        $imglarge->scale(15);
        $imglarge->quality_jpg = 100;
        $imglarge->save("uploads/thumb/large$nom");
            
        $image->scale(7);
        $imglarge->quality_jpg = 80;
        $image->save("uploads/thumb/thumb$nom") ;



        list($width, $height) = getimagesize($path_dir);
        
        if ($width > $height) {
            $type = 'paysage';
        }

        if ($height > $width) {
            $type = 'portrait';
        }

        if ($width == $height){
            $type = 'carre';
        }

        



        
        
        $thumb = "/uploads/thumb/thumb$nom";
        $large = "/uploads/thumb/large$nom";
        $document->setType($type);
        $document->setHeight($height);
        $document->setWidth($width);
        $document->setThumb($thumb);
        $document->setLarge($large);
        $em->persist($document);
        $em->flush();

        //infos sur le document envoyé
        
        return new JsonResponse();
    }
}
