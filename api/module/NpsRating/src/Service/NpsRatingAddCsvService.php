<?php
namespace NpsRating\Service;

use NpsRating\Entity\NpsRating;
use Users\Entity\User;
use Doctrine\Persistence\ObjectManager;

class NpsRatingAddCsvService
{
    private ObjectManager $objectManager;
    private $message;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function getCsv($post)
    {        
        $csvName = $post['csv-file']['tmp_name'];
        $csvSize = $post['csv-file']['size'];
        if ($csvSize > 0) {
            $rows = [];
            $csvfile = fopen($csvName, 'r');
            $counter = 1;
            while ( ( $row = fgetcsv( $csvfile ) ) !== false ) {
                if( $row != null ) {
                    if($counter==1) {
                        $counter++;
                        continue;
                    }
                    $rows[] =   explode(';', trim($row[0]));
                }
            }
            $this->insertCsv($rows);
            fclose($csvfile);
        }
    }

    public function insertCsv($rows) 
    {
        $message = [];
        $line = 1;
        foreach($rows as $row) {
            $user = $this->objectManager->getRepository(User::class)->findOneBy(['referenceNumber' => (int)$row[0]]);
            $line++;
            if($user != NULL) {
                $csvRow = new NpsRating();
                $csvRow->setUser($user);
                $csvRow->setEmail($row[1]);
                $csvRow->setComment($row[5]);
                $csvRow->setRating($row[4]);
                $csvRow->setCustomerName($row[3]);
                $csvRow->setDate(new \DateTime($row[2]));
                $this->objectManager->persist($csvRow);
            } else {
                $message[] = [
                    'line' => 'Błędny id montażysty ( ' . $row[0] .  ' ) w linii: ' . $line,
                ];
            }

        }
        $this->objectManager->flush();
        $this->setMessage($message);
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }
}