<?php

namespace App\Controller\Admin;

use App\Entity\Offres;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Locale;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/dashboard', name: 'admin')]
    public function index(): Response
    {

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        
        return $this->render('admin/dashboard.html.twig');
    }





    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
        // the name visible to end users
        ->setTitle('Nouvsys')
        // you can include HTML contents too (e.g. to link to an image)
        // ->setTitle('<img src="..."> ACME <span class="text-small">Corp.</span>')

        // by default EasyAdmin displays a black square as its default favicon;
        // use this method to display a custom favicon: the given path is passed
        // "as is" to the Twig asset() function:
        // <link rel="shortcut icon" href="{{ asset('...') }}">
        ->setFaviconPath("images/favicon.png")





        // by default, all backend URLs are generated as absolute URLs. If you
        // need to generate relative URLs instead, call this method
        ->generateRelativeUrls()

        // set this option if you want to enable locale switching in dashboard.
        // IMPORTANT: this feature won't work unless you add the {_locale}
        // parameter in the admin dashboard URL (e.g. '/admin/{_locale}').
        // the name of each locale will be rendered in that locale
        // (in the following example you'll see: "English", "Polski")
        // to customize the labels of locales, pass a key => value array
        // (e.g. to display flags; although it's not a recommended practice,
        // because many languages/locales are not associated to a single country)
        ->setLocales([
            'fr' => '🇫🇷 Français',
            'en' => '🇬🇧 English'
        ])
      
    ;
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToUrl('Back to the website', 'fas fa-home', '/');
        yield MenuItem::linkToCrud('Offres', 'fas fa-user', Offres::class);
        

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
