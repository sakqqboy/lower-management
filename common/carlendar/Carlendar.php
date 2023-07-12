<?php

namespace common\carlendar;

use Exception;
use Yii;

class Carlendar
{

	public static function currentMonth($date)
	{
		$dateArr = explode('-', $date);
		$year = $dateArr[0];
		$month = $dateArr[1];
		$day = $dateArr[2];
		$totalDate = Self::countDate($month);
		$startDate = date("l", mktime(0, 0, 0, (int)$month, (int)'01', $year));
		$startPosition = self::firstPosition($startDate);
		$firstDayInMonth = 1;
		//$totalPosition = ($totalDate + $startPosition) - 1;
		$previousPosition = $startPosition >= 0 ? $startPosition - 1 : 0;
		$i = 1;
		$dateValue = [];
		$day = "01";
		while ($i <= $totalDate) {

			$dateValue[$startPosition] = [
				"date" => $year . "-" . $month . "-" . $day . " 00:00:00",
				//"month" => $month
			];
			$day = (int)$day;
			$day += 1;
			if ($day < 10) {
				$day = "0" . $day;
			}
			$i++;
			$startPosition++;
		}
		if ($previousPosition > 0) {
			$previousMonth = self::previousMonth((int)$month, $year, $previousPosition);
			$dateValue = array_merge($previousMonth, $dateValue);
		}
		if (count($dateValue) < 35) {
			$diff = 35 - count($dateValue);
			if ($month == 12) {
				$nexMonth = 1;
				$year++;
			} else {
				$nexMonth = (int)$month + 1;
			}
			if ($nexMonth < 10) {
				$nexMonth = "0" . $nexMonth;
			}
			$n = 1;
			while ($n <= $diff) {
				if ($n < 10) {
					$d = "0" . $n;
				} else {
					$d = $n;
				}
				$next[$n] = [
					"date" => $year . "-" . $nexMonth . "-" . $d . " 00:00:00",
					//"month" => $nexMonth
				];
				$n++;
			}
			$dateValue = array_merge($dateValue, $next);
			//throw new Exception($diff);
		}
		//throw new Exception(print_r($dateValue, true));
		return $dateValue;
	}
	public static function countDate($month)
	{
		$thirty = ["04", "06", "09", "11"];
		$thirtyOne = ["01", "03", "05", "07", "08", "10", "12"];
		if (in_array($month, $thirty)) {
			$date = 30;
		}
		if (in_array($month, $thirtyOne)) {
			$date = 31;
		}
		if ($month == "02") {
			if (($month % 4) == 0) {
				$date = 29;
			} else {
				$date = 28;
			}
		}
		return $date;
	}
	public static function previousMonth($month, $year, $totalDay)
	{
		$previousMonth = $month - 1;

		if ($month == 1) {
			$previousMonth = 12;
			$dayInMonth = 31; //December
			$year = $year - 1;
		} else {

			if ($month < 10) {
				$month = "0" . $previousMonth;
				$previousMonth = $month;
			}
			$dayInMonth = self::countDate($previousMonth);
		}
		$previous = [];
		$i = 1;
		$start = $dayInMonth - ($totalDay - 1);
		while ($start <= $dayInMonth) {
			$previous[$i] = [
				"date" => $year . "-" . $previousMonth . "-" . $start . " 00:00:00",
				//"month" => $previousMonth
			];
			$start++;
			$i++;
		}
		return $previous;
	}
	public static function firstPosition($date)
	{
		switch ($date) {
			case "Monday":
				return 1;
			case "Tuesday":
				return 2;
			case "Wednesday":
				return 3;
			case "Thursday":
				return 4;
			case "Friday":
				return 5;
			case "Saturday":
				return 6;
			case "Sunday":
				return 7;
		}
	}
}
