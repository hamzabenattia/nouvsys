<?php

namespace App\Service;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use DateTime;
use App\Service\EmailSender;
use DateTimeImmutable;



class GoogleMeetService
{
    private $client;

   public function __construct(string $credentialsPath, string $tokenPath)
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Your App Name');
        $this->client->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
        $this->client->setAuthConfig($credentialsPath);
        $this->client->setAccessType('offline');

        // Load the access token if it exists
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);

            // Refresh the token if it’s expired
            if ($this->client->isAccessTokenExpired()) {
                $newAccessToken = $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                if (!isset($newAccessToken['error'])) {
                    file_put_contents($tokenPath, json_encode($newAccessToken));
                    $this->client->setAccessToken($newAccessToken);
                } else {
                    throw new \Exception('Failed to refresh access token: ' . $newAccessToken['error']);
                }
            }
        } else {
            throw new \Exception('Access token not found. Please authenticate via /google-auth.');
        }
    }

    public function createMeeting(string $title, string $email, DateTimeImmutable $dateTime): string
    {
        $service = new Google_Service_Calendar($this->client);

        $event = new \Google_Service_Calendar_Event([
            'summary' => $title,
            'start' => ['dateTime' => $dateTime->format('c')],
            'end' => ['dateTime' => $dateTime->modify('+1 hour')->format('c')],
            'attendees' => [
                [
                    'email' => $email,
                ]
                ],
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => uniqid(),

                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet']
                    
                ]
            ]
        ]);

        $event = $service->events->insert('primary', $event, ['conferenceDataVersion' => 1]);
        $meetLink = $event->getHangoutLink();


        return $meetLink;
    }

}
