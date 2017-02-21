<?php
/**
 * Created by PhpStorm.
 * User: pkupe
 * Date: 2017-02-14
 * Time: 19:03
 */

namespace StackExchangeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use StackExchangeBundle\Model\TagManager as BaseTagManager;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class TagManager extends BaseTagManager
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
        $this->repository = $em->getRepository(Tag::class);

        $metadata = $em->getClassMetadata(Tag::class);

        $this->class = $metadata->name;
    }

    /**
     * Finds one comment thread by the given criteria
     *
     * @param  array           $criteria
     * @return Question
     */
    public function findTagBy(array $criteria)
    {
        return $this->repository->findOneBy($criteria);
    }

    /**
     * {@inheritDoc}
     */
    public function findTagsBy(array $criteria)
    {
        return $this->repository->findBy($criteria);
    }

    /**
     * @param  string          $id
     * @return Question
     *
     */
    public function findTagById($id)
    {
        return $this->findTagBy(array('id' => $id));
    }

    /**
     * Finds all threads.
     *
     * @return array of Question
     */
    public function findAllTags()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritDoc}
     */
    public function isNewTag(Tag $tag)
    {
        return !$this->em->getUnitOfWork()->isInIdentityMap($tag);
    }

    /**
     * Returns the fully qualified comment thread class name
     *
     * @return string
     **/
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Saves Question
     *
     * @param Tag $tag
     */
    protected function doSaveTag(Tag $tag)
    {
        $this->em->persist($tag);
        $this->em->flush();
    }

    /**
     * @param $string
     * @param string $delimiter
     * @return ArrayCollection
     */
    public function createTagsFromString($string, $delimiter = ",")
    {
        $tags = explode($delimiter, $string);

        $return =  new ArrayCollection();

        foreach ($tags as $tag) {
            $oTag = $this->findTagBy(['name' => $tag]);
            if ($oTag == null || $oTag == false) {
                $oTag = $this->createTag();
                $oTag->setName($tag);
                $return->add($oTag);
            } else {
                $return->add($oTag);
            }
        }

        return $return;
    }

}