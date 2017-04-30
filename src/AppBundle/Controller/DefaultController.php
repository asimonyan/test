<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package AppBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Template()
     * @return array
     * @param Request $request
     */
    public function indexAction(Request $request)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // get paginator service
        $paginator = $this->get('knp_paginator');

        // get items query for pagination
        $query = $em->getRepository('AppBundle:Item')->findAllForPagination();

        $itemPerPage = $this->container->getParameter('item_per_page');

        // generate items
        $items = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $itemPerPage
        );

        // find all categories name
        $categories = $em->getRepository("AppBundle:Category")->findAllNames();

        return array('items' => $items, 'categories' => $categories);
    }

    /**
     * @Route("/category/{name}", name="category")
     * @Template()
     * @param $name
     * @return array
     * @param Request $request
     */
    public function categoryAction(Request $request, $name)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // get paginator service
        $paginator = $this->get('knp_paginator');

        // get item
        $query = $em->getRepository("AppBundle:Item")
            ->findAllByCategoriesForPagination($name);

        $itemPerPage = $this->container->getParameter('item_per_page');

        // generate items
        $items = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            $itemPerPage
        );

        return array('items' => $items);
    }

    /**
     * @Route("/item/{name}", name="item")
     * @Template()
     * @return array
     * @param $name
     */
    public function itemAction($name)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // get item
        $item = $em->getRepository("AppBundle:Item")->findOneByName($name);

        if(!$item){
            throw $this->createNotFoundException('item not found');
        }

        return array('item' => $item);
    }

    /**
     * @Route("/call-back", name="call_back")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function callBack(Request $request)
    {
        if ($request->isMethod("POST")){
            return new Response('Message send');
        }
        return array();
    }
}
