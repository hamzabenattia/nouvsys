<?php

namespace App\Twig\Components;

use App\Repository\OffresRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;


#[AsLiveComponent (name: 'offres_filtre', template: '/components/offres_filtre.html.twig')]
class OffresFillterComponents{

    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public string $location = "";

    #[LiveProp(writable: true)]
    public string $type = "";

    public function __construct(private OffresRepository $offresRepository)
    {
    }

    public function getOffers()
    {
        // example method that returns an array of Products
        return $this->offresRepository->search($this->location, $this->type);
    }
}