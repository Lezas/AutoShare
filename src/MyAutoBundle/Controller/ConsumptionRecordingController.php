<?php

namespace MyAutoBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MyAutoBundle\Entity\Auto;
use MyAutoBundle\Entity\Post;
use MyAutoBundle\Entity\ServiceHistory;
use MyAutoBundle\Entity\Thread;
use MyAutoBundle\Entity\User;
use MyAutoBundle\Form\Type\AutoType;
use MyAutoBundle\Form\Type\PostType;
use MyAutoBundle\Form\Type\ServiceHistoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class DefaultController
 * @package MyAutoBundle\Controller
 */
class ConsumptionRecordingController extends Controller
{

}