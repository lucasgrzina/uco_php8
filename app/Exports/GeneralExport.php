<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;

class GeneralExport implements FromView, ShouldAutoSize, WithEvents
{
	use Exportable, RegistersEventListeners;

	public function __construct($data,$header=[],$format=[])
	{
	    $this->data = $data;
	    $this->header = $header;
	    $this->format = $format;
	}

    public function view(): View
    {
        return view('exports.general', [
            'data' => $this->data,
            'header' => $this->header,
            'format' => $this->format
        ]);
    }

	public static function afterSheet(AfterSheet $event)
    {
    	$headerRange 	= 'A1'.':'.$event->sheet->getHighestColumn().'1';
    	$tableRange  	= 'A1'.':'.$event->sheet->getHighestColumn().($event->sheet->getHighestRow()-2);
    	$totalsRange	= 'A'.$event->sheet->getHighestRow().':B'.$event->sheet->getHighestRow();

		$tableStyle = [
		  'borders' =>	[
		      'allBorders' => [
		          'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
		          'color' => ['argb' => '000000'],
		      ]
		  ]
		];

		$headerStyle = $filtersStyle = [
            'font' => [
                'bold' => true
            ]
		];

		$event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);


        $event->sheet->getStyle($headerRange)->applyFromArray($headerStyle);

        $event->sheet->getStyle($tableRange)->applyFromArray($tableStyle);

        //$event->sheet->getStyle($totalsRange)->applyFromArray($tableStyle);


    }

}
