<?php

namespace App\Controller\Admin\General;

use App\Entity\General\ObtentionNiveau;
use App\Entity\General\Grade;
use App\Entity\General\User;
use App\Form\General\ObtentionNiveauType;
use App\Repository\General\ObtentionNiveauRepository;
use App\Repository\General\GradeRepository;
use App\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use \App\Entity\General\ObtentionNiveauRecherche;
use \App\Form\General\ObtentionNiveauRechercheType;

/**
 * @Route("/admin/grade")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminObtentionNiveauController extends BaseController {

    protected function getThemeCourant() : string
    {
        return "General";
    }
    
    protected function getMenuCourant() : string
    {
        return "AdminObtentionNiveau";
    }

    
    public function __construct(EntityManagerInterface $em) {
       parent::__construct($em);
    }
    
    /**
     * @Route("/", name="admin.obtentionNiveau.index", methods={"GET"})
     */
    public function index(Request $Requete, ObtentionNiveauRepository $obtentionNiveauRepository): Response {
        $recherche = new ObtentionNiveauRecherche();
        $form = $this->createForm(ObtentionNiveauRechercheType::class, $recherche);
        $form->handleRequest($Requete);

        $obts = $obtentionNiveauRepository->findAllAvecJointure($recherche);
        
        return $this->monRender('admin/general/obtentionNiveau/index.html.twig', [
                    'obtentionNiveaux' => $obts,
                    'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="admin.obtentionNiveau.new", methods={"GET","POST"})
     */
    public function new(Request $request): Response {
        $obtentionNiveau = new ObtentionNiveau();
        $obtentionNiveau->setDate(new \DateTime('now'));
        $form = $this->createForm(ObtentionNiveauType::class, $obtentionNiveau);
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()) {
            if ($obtentionNiveau->getUser()->hasGrade($obtentionNiveau->getNiveau())) {
                dump($obtentionNiveau->getDate());        
                $this->addFlash('error', 'Le joueur avait déjà ce grade.');
                return $this->redirectToRoute('admin.obtentionNiveau.index');
            }

            $gradeActuel = $this->getDoctrine()->getRepository(Grade::class)->getGrades($obtentionNiveau->getUser()->getId(), 
                    $obtentionNiveau->getNiveau()->getTheme()->getNom())[0];
            if ( $obtentionNiveau->getNiveau()->getNum() > $gradeActuel->getNum() )
            {
                $this->addFlash('warning', 'Nouveau grade actuel : ' . $obtentionNiveau->getNiveau()->getNum() );
                $gradeActuel->setNum($obtentionNiveau->getNiveau()->getNum());
            }
            
            $this->em->persist($obtentionNiveau);
            $this->em->flush();
            $this->addFlash('success', 'Grade ajouté avec succès.');

            return $this->redirectToRoute('admin.obtentionNiveau.index');
        }

        return $this->monRender('admin/general/obtentionNiveau/new.html.twig', [
                    'obtentionNiveau' => $obtentionNiveau,
                    'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin.obtentionNiveau.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ObtentionNiveau $obtentionNiveau): Response {
        $form = $this->createForm(ObtentionNiveauType::class, $obtentionNiveau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();
            $this->addFlash('success', 'Grade modifié avec succès.');            
            $this->addFlash('error', "Attention, le plus haut grade n'est peut être plus à jour.");

            return $this->redirectToRoute("admin.obtentionNiveau.index");
        }

        return $this->monRender('admin/general/obtentionNiveau/edit.html.twig', [
                    'obtentionNiveau' => $obtentionNiveau,
                    'form' => $form->createView(),
        ]);
    }

    
    /**
     * @Route("/{id}-{t}-{num}/setPlusHaut", name="admin.obtentionNiveau.setPlusHaut", methods={"GET","POST"})
     */
    public function setPlusHaut(User $user, $t, $num): Response {
        $gradeActuel = $this->getDoctrine()->getRepository(Grade::class)->getGrades($user->getId(), $t)[0];
        $this->donnerGrade($gradeActuel, $user,$t, $num);
        
        return $this->redirectToRoute('admin.obtentionNiveau.index');
    }
    
    /**
     * @Route("/{id}", name="admin.obtentionNiveau.delete", methods={"DELETE"})
     */
    public function delete(Request $request, ObtentionNiveau $obtentionNiveau): Response {
        if ($this->isCsrfTokenValid('delete' . $obtentionNiveau->getId(), $request->request->get('_token'))) {
             $gradeActuel = $this->getDoctrine()->getRepository(Grade::class)->getGrades($obtentionNiveau->getUser()->getId(), 
                    $obtentionNiveau->getNiveau()->getTheme()->getNom())[0];
            if ( $obtentionNiveau->getNiveau()->getNum() == $gradeActuel->getNum() )
            {
                dump($gradeActuel);
                $this->addFlash('warning', 'Nouveau grade actuel : ' . strval($gradeActuel->getNum() - 1));
                $gradeActuel->setNum($gradeActuel->getNum() - 1);
            }
            
            $this->em->remove($obtentionNiveau);
            $this->em->flush();
            $this->addFlash('success', 'Grade supprimé avec succès.');
        }

        return $this->redirectToRoute('admin.obtentionNiveau.index');
    }

}
