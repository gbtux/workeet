<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class UserController
 * @package App\Controller\Api
 * @Rest\Route("/api/users")
 */
class UserController extends AbstractController
{
    /**
     * @Rest\Get("")
     * @Rest\View(serializerGroups={"simple"})
     */
    public function liste(Request $request)
    {
        $search = $request->get('search');
        $users = $this->getDoctrine()->getRepository('App:Utilisateur')->searchByUsernameOrEmail($search);
        return $users;
    }


}
