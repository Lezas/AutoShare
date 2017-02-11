<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-08
 * Time: 22:13
 */

namespace MyAutoBundle\Controller;

use MyAutoBundle\Document\CarProfile;
use MyAutoBundle\Entity\Auto;
use MyAutoBundle\Form\Type\SearchFieldType;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;
use ONGR\ElasticsearchDSL\Query\TermLevel\TermQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{

    /**
     * @param Request $request
     * @Route("/search", name="search")
     * @return Response
     *
     */
    public function searchAction(Request $request)
    {

        $form = $this->createForm(SearchFieldType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            dump($form);
            dump($form->getData()['Search']);

            $keyword = $form->getData()['Search']  ;

            $manager = $this->get('es.manager');
            $repo = $manager->getRepository('MyAutoBundle:CarProfile');
            $search = $repo->createSearch();

            $queryStringQuery = new QueryStringQuery($keyword);
            $search->addQuery($queryStringQuery);

            $results = $repo->findDocuments($search);

            dump($results);

            exit;
        }

        return $this->render('@MyAuto/Default/searchField.html.twig',[
            'form' => $form->createView(),
            'title' => 'Edit post',
        ]);
/*
        $manager = $this->get('es.manager');
        $repo = $manager->getRepository('MyAutoBundle:CarProfile');
        $content = $repo->find(1);

        $search = $repo->createSearch();

        $auto = new Auto();
        $auto->setUser($this->getUser());



        $result = $repo->findDocuments($search);
        $auto = $result->current()->id;

        dump($result->current());

        dump($result->count());
        $result->next();
        dump($result->current());
        $result->next();

        $auto = $this->getDoctrine()->getManager()->getRepository('MyAutoBundle:Auto')->find($auto);

        dump($auto);

        $carProfile = new CarProfile();
        $carProfile->id = 1;
        $carProfile->name = "something";

        $manager->remove($carProfile);
        $manager->commit();

        dump($content);
*/
    }

}