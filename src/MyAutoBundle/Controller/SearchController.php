<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-08
 * Time: 22:13
 */

namespace MyAutoBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use MyAutoBundle\Form\Type\SearchFieldType;
use ONGR\ElasticsearchDSL\Query\FullText\QueryStringQuery;
use StackExchangeBundle\Document\QuestionDocument;
use StackExchangeBundle\Document\TagDocument;
use StackExchangeBundle\Entity\Question;
use StackExchangeBundle\Entity\Tag;
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
        $questions = new ArrayCollection();

        $mana = $this->get('es.manager')->getClient()->ping();

        if ($form->isValid()) {

            $keyword = $form->getData()['Search'];

            $manager = $this->get('es.manager');
            $repo = $manager->getRepository('StackExchangeBundle:QuestionDocument');
            $search = $repo->createSearch();

            $queryStringQuery = new QueryStringQuery($keyword);
            $search->addQuery($queryStringQuery);

            $results = $repo->findDocuments($search);

            for ($i = 1; $i <= $results->count(); $i++) {
                $qId = $results->current()->question_id;
                $question = $this->get('stack_exchange.manager.question')->findQuestionById($qId);
                $questions->add($question);
                $results->next();
            }

        }

        return $this->render('@MyAuto/Default/search.html.twig', [
            'questions' => $questions,
        ]);

    }

    /**
     * @param Request $request
     * @Route("/search/new", name="new_search")
     * @return Response
     *
     */
    public function newSearchAction(Request $request)
    {
        $form = $this->createForm(SearchFieldType::class);

        return $this->render('@MyAuto/Default/searchField.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit post',
        ]);
    }

    /**
     * @param Request $request
     * @Route("/update/everything", name="update_everything")
     * @return Response
     *
     */
    public function updateEverythingAction(Request $request)
    {
        $form = $this->createForm(SearchFieldType::class);

        $questions = $this->get('stack_exchange.manager.question')->findAllQuestion();

        $es = $this->get('es.manager');

        /** @var Question $question */
        foreach ($questions as $question) {
            $qDoc = new QuestionDocument();
            $qDoc->id = $question->getId();
            $qDoc->setQuestionId($question->getId());
            $qDoc->setTitle($question->getTitle());
            $qDoc->setBody($question->getText());

            /** @var Tag $tag */
            foreach ($question->getTags()->toArray() as $tag) {
                $tDoc = new TagDocument();
                $tDoc->setTitle($tag->getName());
                $qDoc->addTag($tDoc);
            }

            $es->persist($qDoc);
        }

        $es->commit();

        dump("success");
        exit;

        return $this->render('@MyAuto/Default/searchField.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit post',
        ]);
    }

}