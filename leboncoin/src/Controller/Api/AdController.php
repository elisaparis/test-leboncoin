<?php

namespace App\Controller\Api;

use App\Entity\Ad;
use App\Entity\Category;
use App\Entity\MetaAd;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdController
 * @package App\Controller
 * @Route("/ads", name="ad_controller")
 */
class AdController extends AbstractFOSRestController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Get all ads
     *
     * @Rest\Get("")
     *
     * @return View
     */
    public function getAds()
    {
        $ads = $this->em->getRepository(Ad::class)->findAll();

        return View::create($ads, Response::HTTP_OK);
    }

    /**
     * Get ad by id
     *
     * @Rest\Get("/{id}")
     *
     * @param int $id
     * @return object|void
     */
    public function getAd(int $id)
    {
        $ad = $this->em->getRepository(Ad::class)->find($id);

        if (!$ad) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Ad not found.');
        }

        return View::create($ad, Response::HTTP_OK);
    }

    /**
     * Create new ad
     *
     * @Rest\Post("")
     *
     * @param Request $request
     * @return View
     */
    public function createAd(Request $request)
    {
        if ($request->get('user') === null) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must be associated to a user.');
        }

        if ($request->get('title') === null || $request->get('content') === null) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must contain title and content.');
        }

        if ($request->get('categories') === null || empty($request->get('categories'))) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must be associated to at least one category.');
        }

        if ($request->get('metas') === null || empty($request->get('metas'))) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must have complementary fields.');
        }

        $user = $this->em->getRepository(User::class)->find(intval($request->get('user')));

        if (!$user) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'User not found.');
        }

        $ad = new Ad();
        $ad->setUser($user);
        $ad->setTitle($request->get('title'));
        $ad->setContent($request->get('content'));

        foreach($request->get('categories') as $category) {
            $category = $this->em->getRepository(Category::class)->find(intval($category));

            if (!$category) {
                throw new HttpException(Response::HTTP_NOT_FOUND, 'Category not found.');
            }

            $ad->addCategory($category);
        }

        foreach($request->get('metas') as $meta) {
            $meta = new MetaAd(key($meta), $meta[key($meta)]);
            $meta->setAd($ad);
            $this->em->persist($meta);
            $ad->addMeta($meta);
        }

        $this->em->persist($ad);
        $this->em->flush();

        if ($this->em->contains($ad)) {
            return View::create($ad, Response::HTTP_CREATED);
        }
        else {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Oops, an error has occurred.');
        }
    }
}