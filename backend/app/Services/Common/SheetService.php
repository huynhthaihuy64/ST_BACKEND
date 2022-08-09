<?php

namespace App\Services\Common;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

class SheetService
{
    /**
     * @var Client
     * @var Sheets
     * 
     * @param string $documentID
     * @param string $range
     */
    private $client, $service, $documentId, $range;

    /**
     * GoogleClient constructor.
     * 
     * @var Sheets
     */
    public function __construct()
    {
        $this->client = $this->getclient();
        $this->service = new Sheets($this->client);
        $this->documentId = config('sheet.document_id');
        $this->range = config('sheet.range');
    }

    /**
     * Get data client of Google
     * 
     */
    private function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Google Sheets API');
        $client->setScopes(Sheets::SPREADSHEETS);
        $client->setAuthConfig('credentials.json');
        $client->setAccessType('offline');

        return $client;
    }

    /**
     * Read value from google
     */
    public function readSheets()
    {
        $doc = $this->service->spreadsheets_values->get($this->documentId,$this->range);
        
        return $doc;
    }
    
    /**
     * Append value for google sheet
     * 
     * @param array $value
     * @var ValueRange
     */
    public function appendSheets(array $values)
    {
        /*write in order: Name, Email, Phone, CV, Avatar,...
        VD: [$user->name,$user->email,$user->phone,$user->CV,$user->avatar]
        ]
        */
        $body = new ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => 'USER_ENTERED'
        ];
        return $this->service->spreadsheets_values->append($this->documentId, $this->range, $body, $params);
    }

    /**
     * Update value for google sheet
     * 
     * @var int $id
     * @param array $value
     * @var ValueRange
     */
    public function updateSheets(int $id, array $values)
    {
        /*update in order: Name, Email, Phone, CV, Avatar,...
        VD: [$user->name,$user->email,$user->phone,$user->CV,$user->avatar]
        ]
        */
        $body = new ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];

        return $this->service->spreadsheets_values->update($this->documentId, 'A'.$id.':Z', $body, $params);
    }
}
