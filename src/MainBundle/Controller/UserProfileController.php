<?php
/**
 * Created by Lezas.
 * Date: 2017-04-02
 * Time: 12:12
 */

namespace MainBundle\Controller;

use MainBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserProfileController extends Controller
{
    /**
     * @Route("/user/{username}", name="user_profile_main")
     * @param null $username
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showUserProfileAction($username = null)
    {
        $user = $this->getUser();

        if (null != $username) {
            $userManager = $this->get('fos_user.user_manager');
            $user = $userManager->findUserByUsername($username);

        }

        if (null == $user) {
            return $this->redirectToRoute('Dashboard');
        }

        return $this->render('MainBundle:UserProfile:index.html.twig', [
            'user' => $user,
        ]);
    }


    /**
     * @Route("/garage", name="garage")
     */
    public function userCarListAction()
    {
        /**
         * @var $user User
         */
        $user = $this->getUser();
        $cars = $user->getCars();

        return $this->render('MainBundle:Default:garage.html.twig', [
            'ownedAutos' => $cars,
        ]);
    }
}