<?php

namespace SearchBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use SearchBundle\Form\SearchFieldType;
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

        return $this->render('@Search/Default/search.html.twig', [
            'questions' => $questions,
        ]);

    }

    /**
     * @param Request $request
     * @Route("/search/questions", name="search_questions")
     * @return Response
     *
     */
    public function searchQuestionsAction(Request $request)
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

        return $this->render('@Search/Default/searchQuestions.html.twig', [
            'questions' => $questions,
        ]);

    }

    /**
     * @param Request $request
     * @Route("/search/cars", name="search_cars")
     * @return Response
     *
     */
    public function searchForCarsAction(Request $request)
    {
        $form = $this->createForm(SearchFieldType::class);

        $form->handleRequest($request);
        $cars = new ArrayCollection();

        $mana = $this->get('es.manager')->getClient()->ping();

        if ($form->isValid()) {

            $keyword = $form->getData()['Search'];

            $manager = $this->get('es.manager');
            $repo = $manager->getRepository('CarShowBundle:CarProfileDocument');
            $search = $repo->createSearch();

            $queryStringQuery = new QueryStringQuery($keyword);
            $search->addQuery($queryStringQuery);

            $results = $repo->findDocuments($search);


            for ($i = 1; $i <= $results->count(); $i++) {
                $qId = $results->current()->id;
                $car = $this->get('car_show.manager.car')->findCarById($qId);
                $cars->add($car);
                $results->next();
            }

        }

        return $this->render('@Search/Default/searchCars.html.twig', [
            'cars' => $cars,
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

        return $this->render('@Search/Default/searchField.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit post',
        ]);
    }

    /**
 * @param Request $request
 * @Route("/search/new/cars", name="new_search_cars")
 * @return Response
 *
 */
    public function newSearchCarsAction(Request $request)
    {
        $form = $this->createForm(SearchFieldType::class);

        return $this->render('@Search/Default/searchCarsField.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/search/new/questions", name="new_search_questions")
     * @return Response
     *
     */
    public function newSearchQuestionsAction(Request $request)
    {
        $form = $this->createForm(SearchFieldType::class);

        return $this->render('@Search/Default/searchQuestionsField.html.twig', [
            'form' => $form->createView(),
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

        return $this->render('@Search/Default/searchField.html.twig', [
            'form' => $form->createView(),
            'title' => 'Edit post',
        ]);
    }

}