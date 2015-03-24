<?php

namespace Qobo\BankTools\Currency;

use PHPExcel_IOFactory;

class CurrencyConverter
{
    public  $rates = array();
    public $thousandsSeparator ;
    public $decimalSeparator;

    function __construct($thousandsSeparator=',',$decimalSeparator='.') {

        $this->thousandsSeparator=$thousandsSeparator;
        $this->decimalSeparator=$decimalSeparator;

    }


    public  function setRates($rateFilePath, $options)
    {
        $this->rates= $this->loadRatesFromExcelFile($rateFilePath, $options);
    }

    public function validateUserInput($amount, $currencyCode)
    {
        $error_messages = array();
        $result = array();

        if (empty($amount)) {
            $error_messages['foreing_amount'] = 'Please give a amount to convert';
        }

        if (empty($currencyCode)) {
            $error_messages['from_currency'] = 'Please give currency type';
        }


        if (!$this->validateCurrency($amount)) {

            $error_messages['from_currency_valid'] = 'Please give a valid currency';
        }

        if (count($error_messages)) {

            $result['status'] = 'ERROR';
            $result['error_messages'] = $error_messages;

        } else {

            $result['status'] = 'OK';

        }
        return $result;

    }

    public function validateCurrency($currency)
    {
        return preg_match('/\b\d{1,3}(?:,?\d{3})*(?:\.\d{2})?\b/', $currency);

    }

    public function convertCurrency($action, $amount, $currencyCode)
    {
        $rate =  $this->getCurrencyRate($action, $currencyCode);
        $amount=$this->getFloatAmount($amount);

        if (strtolower($action) == "sell") {

            return round($amount * $rate, 2);

        } else if (strtolower($action) == "buy") {

            return round($amount / $rate, 2);

        }

    }

    public function  getCurrencyRate($action, $currencyCode)
    {
        foreach ($this->rates as $rate) {

            if( strtoupper($rate['ccy'])==strtoupper($currencyCode)){

                return $rate[strtolower($action)];
            }
        }

        return null;
    }

    public  function formatToCurrency($val, $r = 2)
    {
        $n = $val;
        $c = is_float($n) ? 1 : number_format($n, $r);
        $d = $this->decimalSeparator;
        $t = $this->thousandsSeparator;
        $sign = ($n < 0) ? '-' : '';
        $i = $n = number_format(abs($n), $r);
        $j = (($j = $i . length) > 3) ? $j % 3 : 0;

        return $sign . ($j ? substr($i, 0, $j) + $t : '') . preg_replace('/(\d{3})(?=\d)/', "$1" + $t, substr($i, $j));

    }

    public function loadRatesFromExcelFile($filePath, $options)
    {
        //Not generic file parsing
        $excelReader = PHPExcel_IOFactory::createReaderForFile($filePath);

        $excelReader->setReadDataOnly(true);

        $excelObj = $excelReader->load($filePath);

        //get the second sheet of the excel file
        $sheet1 = $excelObj->getSheet($options['sheet'])->toArray(null, true, true, true);

        $resultArray = array();

        for ($i = $options['start_row']; $i <= count($sheet1); $i++) {

            array_push($resultArray, array('ccy' => $sheet1[$i][$options['ccy_col']], 'sell' => $sheet1[$i][$options['sell_col']], 'buy' => $sheet1[$i][$options['buy_col']]));

        }

        return $resultArray;
    }

    public function getRates()
    {
        return $this->rates;
    }

    public  function getFloatAmount($money)
    {
        $cleanString = preg_replace('/([^0-9\.,])/i', '', $money);
        $onlyNumbersString = preg_replace('/([^0-9])/i', '', $money);

        $separatorsCountToBeErased = strlen($cleanString) - strlen($onlyNumbersString) - 1;

        $stringWithCommaOrDot = preg_replace('/([,\.])/', '', $cleanString, $separatorsCountToBeErased);
        $removedThousendSeparator = preg_replace('/(\.|,)(?=[0-9]{3,}$)/', '',  $stringWithCommaOrDot);

        return (float) str_replace(',', '.', $removedThousendSeparator);
    }
}