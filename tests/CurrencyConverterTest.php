<?php
/**
 * Test Currency Converter Functionality
 *
 */


include_once 'src/Currency/CurrencyConverter.php';
include_once 'vendor/autoload.php';

class EuroBankCurrencyToolTest extends PHPUnit_Framework_TestCase
{


    /**
     * Dataprovider for user input for currency converter
     * amount
     * currency
     *
     */

    public function userInputCurrencyConverter()
    {

        return array(
            array('10000.00', 'CAD'),
            array('10.00', 'AUD'),
            array('10000', 'BGN'),
        );
    }


    public function currencyConverter()
    {

        return array(
            array('SELL', '100.00', 'CAD', '80'),
            array('BUY', '100.00', 'AUD', '125'),
            array('SELL', '10000', 'BGN', '8000'),
        );
    }

    /**
     *$action
     *$currencyCode
     * $expectedResult
     */
    public function getExcelRates()
    {
        return array(
            array('SELL', 'USD', 1.1459745),
            array('BUY', 'JPY', 139.8522),
            array('SELL', 'GBP', 0.7598745),
            array('BUY', 'SEK', 9.612021),
            array('SELL', 'RON', 4.414998),
        );
    }

    /**
     *
     * @dataProvider userInputCurrencyConverter
     */
    public function dtest1_validateUserInputCurrencyConverter($ammount, $currencyCode)
    {

        $currencyConverter = new CurrencyConverter();

        $result = $currencyConverter->validateUserInput($ammount, $currencyCode);

        $this->assertEquals($result['status'], 'OK', "Validation function result does not much with the expected one");

    }


    /**
     *
     * @dataProvider currencyConverter
     */
    public function dtest1_convertCurrency($action, $amount, $currencyCode, $expectedAmount)
    {

        $currencyConverter = new CurrencyConverter();

        $result = $currencyConverter->convertCurrency($action, $amount, $currencyCode);

        $this->assertEquals($result, $expectedAmount, "Wrong currency calculations");

    }

    /**
     *
     *
     */
    public function test_loadRatesFromExcelFile()
    {
        $options = array(
            'sheet' => 1,
            'start_row' => 2,
            'ccy_col' => 'A',
            'sell_col' => 'B',
            'buy_col' => 'C',

        );

        CurrencyConverter::init('C:\Users\c.christoforou\Development\eurobankTools\tests\TestFiles\FX_Rates_MorningOpenDay.xls', $options);

        $currencyConverter = new CurrencyConverter();

        $rates = $currencyConverter->getRates();

        $this->assertNotEmpty(count($rates), "Rates did not load successfully - Rates array len is zero");

    }

    /**
     *
     * @dataProvider getExcelRates
     */
    public function test_getCurrencyRate($action, $currencyCode, $expectedRate)
    {
        $options = array(
            'sheet' => 1,
            'start_row' => 2,
            'ccy_col' => 'A',
            'sell_col' => 'B',
            'buy_col' => 'C',

        );

        CurrencyConverter::init('C:\Users\c.christoforou\Development\eurobankTools\tests\TestFiles\FX_Rates_MorningOpenDay.xls', $options);

        $currencyConverter = new CurrencyConverter();

        $rate = $currencyConverter->getCurrencyRate($action, $currencyCode);

        $this->assertEquals($rate,$expectedRate,'Rate from excel dont much the retrieved one');

    }


}
