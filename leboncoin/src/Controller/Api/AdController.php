<?php

namespace App\Controller\Api;

use App\Entity\Ad;
use App\Entity\Category;
use App\Entity\MetaAd;
use App\Entity\User;
use App\Helper\AdHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;

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
     * @SWG\Response(
     *     response=200,
     *     description="Returns all ads.",
     * )
     * @SWG\Tag(name="Ad")
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
     * @SWG\Response(
     *     response=200,
     *     description="Returns an ad by id.",
     * )
     * @SWG\Tag(name="Ad")
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
     * @SWG\Response(
     *     response=200,
     *     description="Creates an ad.",
     * )
     * @SWG\Parameter(
     *      name="user",
     *      in="formData",
     *      description="User id",
     *      required=true,
     *      type="integer"
     *  ),
     * @SWG\Parameter(
     *      name="title",
     *      in="formData",
     *      description="Title of the ad",
     *      required=true,
     *      type="string"
     *  ),
     * @SWG\Parameter(
     *      name="content",
     *      in="formData",
     *      description="Content of the ad",
     *      required=true,
     *      type="string"
     *  ),
     * @SWG\Parameter(
     *      name="categories",
     *      in="formData",
     *      description="Array of category ids",
     *      required=true,
     *      type="array",
     *      @Swagger\Annotations\Items(type="integer")
     *  )
     * @SWG\Tag(name="Ad")
     *
     * @param Request $request
     * @param AdHelper $adHelper
     * @return View
     */
    public function createAd(Request $request, AdHelper $adHelper)
    {
        $user = $this->em->getRepository(User::class)->find(intval($request->get('user')));
        $adHelper->errorHelper($request, $user);

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

    /**
     * Remove an ad by id
     *
     * @Rest\Delete("/{id}")
     *
     * @SWG\Response(
     *     response=204,
     *     description="Removes an ad by id.",
     * )
     * @SWG\Tag(name="Ad")
     *
     * @param int $id
     * @return View
     */
    public function removeAd(int $id)
    {
        $ad = $this->em->getRepository(Ad::class)->find($id);

        if (!$ad) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Ad not found.');
        }

        $this->em->remove($ad);
        $this->em->flush();

        if (!$this->em->contains($ad)) {
            return View::create(null, Response::HTTP_NO_CONTENT);
        }
        else {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, 'Oops, an error has occurred.');
        }
    }

    /**
     * Update an ad by id
     *
     * @Rest\Put("/{id}")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Updates an ad by id.",
     * )
     * @SWG\Tag(name="Ad")
     *
     * @param int $id
     * @param Request $request
     * @param AdHelper $adHelper
     * @return View
     */
    public function updateAd(int $id, Request $request, AdHelper $adHelper)
    {
        $user = $this->em->getRepository(User::class)->find(intval($request->get('user')));
        $adHelper->errorHelper($request, $user);
        $ad = $this->em->getRepository(Ad::class)->find($id);

        if (!$ad) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Ad not found.');
        }

        $ad->setUser($user);
        $ad->setTitle($request->get('title'));
        $ad->setContent($request->get('content'));

        foreach($request->get('categories') as $categoryId) {
            $category = $this->em->getRepository(Category::class)->find(intval($categoryId));

            if (!$category) {
                throw new HttpException(Response::HTTP_NOT_FOUND, 'Category not found.');
            }

            if (!$ad->getCategories()->contains($category)) {
                $ad->addCategory($category);
            }
        }

        // if category is not in body but is in ad, remove it
        foreach($ad->getCategories() as $category) {
            if (!in_array($category->getId(), $request->get('categories'))) {
                $ad->removeCategory($category);
            }
        }

        // remove all metas and recreate them afterwards
        $adHelper->removeMetas($ad);
        foreach($request->get('metas') as $meta) {
            $meta = new MetaAd(key($meta), $meta[key($meta)]);
            $meta->setAd($ad);
            $this->em->persist($meta);
            $ad->addMeta($meta);
        }

        $ad->setUpdatedAt(new \DateTime());

        $this->em->merge($ad);
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