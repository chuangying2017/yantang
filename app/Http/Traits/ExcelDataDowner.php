<?php

namespace App\Http\Traits;

use Excel;

trait ExcelDataDowner
{

	public function downExcel($e_data, $title)
	{
		Excel::create($title, function($excel) use ($e_data) {

			$excel->sheet('user', function($sheet) use ($e_data) {
				$sheet->fromArray( $e_data );
			});
		})->export('xls');
	}

	public function transformDataToExcel($data)
	{
		$e_datas = [];

		foreach($data as $key => $value) {
			$e_data['卡号'] = $value->ticket->creditCardCoupon->creditCard->card_abstract;
			$e_data['兑换时间'] = (string)$value->created_at;
			$e_data['来源特权'] = $value->ticket->creditCardCoupon->privilege->name;
			$e_data['兑换商家'] = $value->client->name;
			$e_data['兑换详情'] = $value->detail->detail;
			$e_data['优惠券'] = $value->ticket->coupon->name;
			$e_data['兑换码'] = $value->ticket->ticket_no;

			$e_datas[$key] = $e_data;
		}

		return $e_datas;
	}

	public function transformReportExcel($data)
	{

		$e_datas = [];
		$clients = $data['clients'];
		$exchanges_data = $data['exchanges_data'];

		foreach($clients as $client)
		{
	    $e_data['商家'] = $client['name'];
	    for($i = 0; $i < count($exchanges_data); $i++){
	    	$weekname = $exchanges_data[$i]['name'] . $exchanges_data[$i]['memo'];
					$e_data[$weekname] = isset($exchanges_data[$i]['data'][$client['id']]) ? $exchanges_data[$i]['data'][$client['id']] : '无' ;
	    }
			$e_datas[] = $e_data;
		}

		return $e_datas;
	}

}
