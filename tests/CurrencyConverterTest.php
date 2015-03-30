<?php
/**
 * Test Currency Converter Functionality
 *
 */

use Qobo\FinancialTools\Currency\CurrencyConverter;

require_once __DIR__.'/../vendor/autoload.php';


class CurrencyToolTest extends PHPUnit_Framework_TestCase
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
            array('SELL', '100.00', 'CAD', '135.81'),
            array('BUY', '100.00', 'AUD', '69.26'),
            array('SELL', '10000', 'BGN', '19167.33'),
            array('SELL', '100.00', 'USD', '114.6'),
        );
    }

    /**
     *action
     *currencyCode
     *expectedResult
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
    public function test_validateUserInputCurrencyConverter($ammount, $currencyCode)
    {

        $currencyConverter=new CurrencyConverter();
        $result=$currencyConverter->validateUserInput($ammount, $currencyCode);

        $this->assertEquals($result['status'], 'OK', "Validation function result does not much with the expected one");

    }


    /**
     *
     * @dataProvider currencyConverter
     */
    public function test_convertCurrency($action, $amount, $currencyCode, $expectedAmount)
    {

        $options = array(
            'sheet' => 1,
            'start_row' => 2,
            'ccy_col' => 'A',
            'sell_col' => 'B',
            'buy_col' => 'C',

        );
        $currencyConverter=new CurrencyConverter();
        $currencyConverter->setRates('tests\TestFiles\rates.xls', $options);

        $result=$currencyConverter->convertCurrency($action, $amount, $currencyCode);

        $this->assertEquals($result, $expectedAmount, "Wrong currency calculations");

    }

    /**
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
        $currencyConverter=new CurrencyConverter();
        $currencyConverter->setRates('tests\TestFiles\rates.xls', $options);


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


        $currencyConverter=new CurrencyConverter();
        $currencyConverter->setRates('tests\TestFiles\rates.xls', $options);
        $rate =$currencyConverter->getCurrencyRate($action, $currencyCode);

        $this->assertEquals($rate,$expectedRate,'Rate from excel dont much the retrieved one');

    }


}
