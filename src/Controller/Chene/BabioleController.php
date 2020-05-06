<?php

namespace App\Controller\Chene;

use App\Entity\Chene\Babiole;
use App\Repository\Chene\BabioleRepository;
use App\Repository\Chene\TypeBabioleRepository;
use \App\Entity\Chene\BabioleRecherche;
use \App\Form\Chene\BabioleRechercheType;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;

use \Liip\ImagineBundle\Imagine\Cache\CacheManager;

/**
 * @Route("/chene/babiole")
 */
class BabioleController extends BaseController {

    /**
     * @var BabioleRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    protected function getThemeCourant() : string
    {
        return "Chêne";
    }
    
    protected function getMenuCourant() : string
    {
        return "Babiole";
    }
    

    public function __construct(BabioleRepository $repository, EntityManagerInterface $em) {
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @route("/toute/{numTypeBabiole}", name="babiole.index")  
     * @var PaginatorInterface $paginator
     * @var Request $Request
     * @return Response
     */
    public function index(TypeBabioleRepository $t_repository, string $numTypeBabiole): Response {
        $babioles = $this->repository->findAllByType($numTypeBabiole);
        $types = $t_repository->findBy([],["num"=>"ASC"]);
        $type = $t_repository->findBy( ["num"=>$numTypeBabiole] );

        return $this->monRender('chene/babiole/index.html.twig', [
                    'babioles' => $babioles,
                    'types' => $types,
                    'typeCourant' => $type[0]
        ]);
    }

    /**
     * @route("/{slug}-{id}", name="babiole.show", requirements={"slug": "[a-z0-9\-]*"})  
     * @param babiole $babiole
     * @return Response
     */
    public function show(babiole $babiole, string $slug): Response {
        if ($babiole->getSlug() !== $slug)
            return $this->redirectToRoute('babiole.show', [
                        'id' => $babiole->getId(),
                        'slug' => $babiole->getSlug()
                            ], 301);

        return $this->monRender('chene/babiole/show.html.twig', [
                    'babiole' => $babiole
        ]);
    }

}
