<?php

namespace App\Controller\Account;

use App\Form\ProfilFormType;
use App\Form\RemoveAccountFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


#[Route('/')]
class HomeController extends AbstractController
{

    #[Route('/compte/mon_compte', name: 'app_account_home_index')]
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

  /*  #[Route('/compte/mon_compte/mon_contrat', name: 'app_account_home_download')]
    public function download(): File
    {
        $user = $this->getUser();
        //si le customer n'a pas de contrat
        if (!$user->getCustomer()->getContractFileName()) {
            throw new Exception
        }
    }*/

}