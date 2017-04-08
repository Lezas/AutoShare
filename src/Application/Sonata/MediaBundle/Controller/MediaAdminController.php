<?php

/*
 * This file is part of the CKEditorSonataMediaBundle package.
 *
 * (c) La Coopérative des Tilleuls <contact@les-tilleuls.coop>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\MediaBundle\Controller;

use CoopTilleuls\Bundle\CKEditorSonataMediaBundle\Controller\MediaAdminController as BaseMediaAdminController;
use Sonata\DoctrineORMAdminBundle\Filter\ClassFilter;
use Sonata\DoctrineORMAdminBundle\Filter\NumberFilter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Adds browser and upload actions
 *
 * @author Kévin Dunglas <kevin@les-tilleuls.coop>
 */
class MediaAdminController extends BaseMediaAdminController
{

    /**
     * Returns the response object associated with the browser action
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws AccessDeniedException
     */
    public function browserAction()
    {

        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }

        $carId = $this->getRequest()->get('car_id');

        $car = $this->get('car_show.manager.car')->findCarById($carId);

        if ($car->getUser() != $this->getUser()) {
            throw new AccessDeniedException();
        }


        $datagrid = $this->admin->getDatagrid();
        $datagrid->setValue('context', null, $this->admin->getPersistentParameter('context'));
        $datagrid->setValue('providerName', null, $this->admin->getPersistentParameter('provider'));
        $datagrid->setValue('car', '=', $car);


        $em = $em = $this->get('doctrine.orm.entity_manager');
        //TODO move to manager
        $qb = $em->getRepository('ApplicationSonataMediaBundle:Media')->createQueryBuilder('q')
            ->addSelect('q.name as HIDDEN media_name')
            ->where('q.car = :car')
            ->addOrderBy('q.updatedAt')
            ->setParameter('car', $car);
        $query = $qb->getQuery();


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->getRequest()->query->getInt('p', 1),
            $this->getRequest()->query->getInt('limit', 10),/*limit per page*/
            [
                'p.createdAt' => 'time'
            ]
        );

        // Store formats
        $formats = array();
        foreach ($datagrid->getResults() as $media) {
            $formats[$media->getId()] = $this->get('sonata.media.pool')->getFormatNamesByContext($media->getContext());
        }


        // set the theme for the current Admin Form

        return $this->render($this->getTemplate('browser'), array(
            'action' => 'browser',
            'datagrid' => $datagrid,
            'formats' => $formats,
            'base_template' => $this->getTemplate('layout'),
            'pagination' => $pagination
        ));
    }

    /**
     * Gets a template
     *
     * @param  string $name
     * @return string
     */
    private function getTemplate($name)
    {
        $templates = $this->container->getParameter('coop_tilleuls_ck_editor_sonata_media.configuration.templates');

        if (isset($templates[$name])) {
            return $templates[$name];
        }

        return null;
    }


}
