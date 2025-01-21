<?php
namespace App\Data;
use DateTime;

class ArchiveData
{
    public array $dates = [
        'tagesschau' => [
            'min' => '',
            'max' => ''
        ],
        'spiegel' => [
            'min' => '',
            'max' => ''
        ]
    ];

    public function __construct()
    {
        $maxDate = new DateTime('-25 hours');
        $TagesschauMinDate = new DateTime('2005-01-01');
        $SpiegelMinDate =  new DateTime('2000-01-01');
        
        $this->dates['tagesschau']['min'] = $TagesschauMinDate;
        $this->dates['spiegel']['min'] = $SpiegelMinDate;
        foreach($this->dates as $key => $value){
            $this->dates[$key]['max'] = $maxDate;
        }
    }
}