<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-14
 * Time: 19:03
 */

namespace CarShowBundle\Entity;

use CarShowBundle\Event\PostEvent;
use CarShowBundle\Events;
use CarShowBundle\Model\CarInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use CarShowBundle\Model\PostManager as BasePostManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PostManager extends BasePostManager
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var EntityRepository
     */
    protected $repository;

    /**
     * @var string
     */
    protected $class;

    /**
     * Constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct(EventDispatcherInterface $dispatcher, EntityManager $em)
    {
        parent::__construct($dispatcher);

        $this->em = $em;
        $this->repository = $em->getRepository(Post::class);

        $metadata = $em->getClassMetadata(Post::class);

        $this->class = $metadata->name;
    }

    /**
     * Finds one comment thread by the given criteria
     *
     * @param  array           $criteria
     * @return Post
     */
    public function findPostBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findPostsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string          $id
     * @return Post
     *
     */
    public function findPostById($id)
    {
        return $this->findPostBy(array('id' => $id));
    }

    /**
     * Finds all Cars.
     *
     * @return array of Post
     */
    public function findAllPosts()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewPost(CarInterface $car)

    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($car);
    }

    /**
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }


    /**
     * Saves Car
     * @param Post $post
     */
    protected function doSavePost(Post $post)
    {
        $this->em->persist($post);
        $this->em->flush();
    }

    /**
     * @param Post $post
     */
    public function deletePost(Post $post)
    {
        $event = new PostEvent($post);
        $this->dispatcher->dispatch(Events::POST_DELETE, $event);
        $post->setDeleted(true);
    }

}