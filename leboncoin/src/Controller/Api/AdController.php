<?php


namespace App\Controller\Api;


use App\Entity\Ad;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
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
     * @Rest\Get("/")
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

        if (!$ad)
            throw new HttpException(Response::HTTP_NOT_FOUND, "Ad not found.");

        return View::create($ad, Response::HTTP_OK);
    }
}