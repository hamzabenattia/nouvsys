<?php

// src/Controller/GoogleAuthController.php
namespace App\Controller;

use Google_Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GoogleAuthController extends AbstractController
{
    #[Route('/google-auth', name: 'google_auth')]
    public function auth(Request $request): RedirectResponse
    {
        $client = new Google_Client();
        $client->setAuthConfig($this->getParameter('kernel.project_dir') . '/src/config/secrets/google-credentials.json');

        // Generate absolute redirect URI
        $redirectUri = $this->generateUrl(
            'google_auth_callback',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $client->setRedirectUri($redirectUri);
        $client->setScopes([\Google_Service_Calendar::CALENDAR_EVENTS]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return new RedirectResponse($client->createAuthUrl());
    }

    #[Route('/google-auth-callback', name: 'google_auth_callback')]
    public function callback(Request $request): Response
    {
        $client = new Google_Client();
        $client->setAuthConfig($this->getParameter('kernel.project_dir') . '/src/config/secrets/google-credentials.json');

        // Generate absolute redirect URI
        $redirectUri = $this->generateUrl(
            'google_auth_callback',
            [],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $client->setRedirectUri($redirectUri);

        $code = $request->query->get('code');
        if (!$code) {
            return new Response('Authorization code missing.', 400);
        }

        try {
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            if (isset($accessToken['error'])) {
                return new Response('Error fetching access token: ' . $accessToken['error'], 400);
            }

            // Store the access token
            file_put_contents(
                $this->getParameter('kernel.project_dir') . '/src/config/secrets/google-token.json',
                json_encode($accessToken)
            );

            return new Response('Authentication successful. Token saved.');
        } catch (\Exception $e) {
            return new Response('Error: ' . $e->getMessage(), 500);
        }
    }
}