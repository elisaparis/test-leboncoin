<?php


namespace App\Helper;


use App\Entity\Ad;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AdHelper
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function errorHelper(Request $request, ?User $user)
    {
        if ($request->get('user') === null) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must be associated to a user.');
        }

        if ($request->get('title') === null || $request->get('title') === '') {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must contain title.');
        }

        if ($request->get('content') === null || $request->get('content') === '') {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must contain content.');
        }

        if ($request->get('categories') === null || empty($request->get('categories'))) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must be associated to at least one category.');
        }

        if ($request->get('metas') === null || empty($request->get('metas'))) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Ad must have complementary fields.');
        }

        if (!$user) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'User not found.');
        }
    }

    public function removeMetas(Ad $ad)
    {
        foreach($ad->getMetas() as $meta) {
            $this->em->remove($meta);
        }

        $this->em->flush();

        return true;
    }
}