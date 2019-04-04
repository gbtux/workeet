<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GroupController
 * @package App\Controller\Api
 * @Rest\Route("/api/groups")
 */
class GroupController extends AbstractController
{
    /**
     * @Rest\Get("")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function liste(Request $request)
    {
        $search = $request->get('search');
        $groups = $this->getDoctrine()->getRepository('App:Groupe')->searchByName($search);
        return $groups;
    }
}
