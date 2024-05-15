<?php

namespace App\Controller\Admin;

use App\Entity\Candidate;
use App\Entity\Offres;
use App\Entity\SpontaneousCandidate;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/dashboard', name: 'admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(OffresCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)

        // return $this->render('admin/dashboard.html.twig');
    }





    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Nouvsys Dashboard')
            ->setFaviconPath("images/favicon.png")
            ->generateRelativeUrls();

    }

    public function configureMenuItems(): iterable
    {
        // yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Accueill', 'fa fa-home', 'app_home');

        yield MenuItem::linkToCrud('Offres', 'fa-solid fa-clipboard-list', Offres::class)->setPermission('ROLE_ADMIN');
        yield MenuItem::subMenu('Candidate', 'fa-solid fa-users')->setSubItems([
            MenuItem::linkToCrud('Candidate Spontanée', 'fa-solid fa-id-badge', SpontaneousCandidate::class),
            MenuItem::linkToCrud('Réponse à une offre', 'fa fa-file-text', Candidate::class),
            MenuItem::linkToCrud('Liste des candidats', 'fa-solid fa-users', User::class),

        ])->setPermission('ROLE_ADMIN');;

        
        yield MenuItem::section('Autres');
        yield MenuItem::linkToRoute('Profile', 'fa fa-user', 'app_profile');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out');

    }
}
