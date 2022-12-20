<?php
namespace NpsRating\Form;

use Laminas\Form\Element;
use Laminas\Form\Form;

class NpsRatingForm extends Form
{
    public function __construct($name = null, $options = [])
    {
        parent::__construct($name, $options);
        $this->addCsv();
    }

    public function addCsv()
    {
        $file = new Element\File('csv-file');
        $file->setAttribute('id', 'csv-file');
        $file->setAttribute('accept', '.csv');
        $this->add($file);
    }
}