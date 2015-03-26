<?php
/**
 * Test Iban Tool Functionality
 *
 */

use Qobo\FinancialTools\Iban\IbanCalculator;

require_once __DIR__.'/../vendor/autoload.php';

class EuroBankIbanToolTest extends PHPUnit_Framework_TestCase {


/*
 * Dataprovider for user input of iban generator
 *
 * Branch code must be not empty
 * Account number must be 16 letters
 */

    public function userInputIbanGeneration() {
    return array(
        array('00001','111111111111','OK'),
        array('00001','111111111111','OK'),
        array('00001','111111111111','OK'),
    );
}


/*
 *Dataprovider of userInput for IbanValidation
 * 28  alphanumeric characters
 */

    public function userInputIbanValidation() {
        return array(
            array('CY43180000011111111111111111','OK'),
            array('CY43180000011111111111111111','OK'),
            array('CY43180000011111111111111111','OK'),
        );
    }


 /*
 * Dataprovider for generateIban
 * 28  alphanumeric characters
 */

    public function generateIban() {
        return array(
            array('0008','300100018181','CY82018000080000300100018181'),
//            array('0008','200100254590','CY65018000080000200100254590'),

        );
    }


    /*
     * Dataprovider for validateIban
     * 28  alphanumeric characters
     */

    public function validateIban() {
        return array(
            array('CY43180000011111111111111111',true),
            array('CY43180000011111111111111111',true),
            array('CY431800000111111111111111111',false),
        );
    }

    /**
     *
     * @dataProvider userInputIbanGeneration
     */

    public function test_validateUserInputIbanGeneration($branch,$accountNumber,$valid){

         $ibanCalculator=new IbanCalculator('180');

        $result= $ibanCalculator->validateUserInputGeneration($branch,$accountNumber);

        $this->assertEquals($valid, $result['status'],"Not correct user inputs for iban generation");

    }


    /**
     *
     * @dataProvider userInputIbanValidation
     */
    public function test_validateUserInputIbanValidation($iban,$valid){

        $ibanCalculator=new IbanCalculator('180');

        $result= $ibanCalculator->validateUserInputValidation($iban);

        $this->assertEquals($valid, $result['status'],"Not correct user inputs for iban validation");

    }

    /**
     *
     * @dataProvider generateIban
     */
    public function test_generateIban($branch,$accountNumber,$expectedIban){

        $ibanCalculator=new IbanCalculator('180');

        $iban= $ibanCalculator->generateIban($branch,$accountNumber);

        $this->assertEquals($expectedIban,$iban ,"Generated Iban is different that the expected one");

    }

    /**
     *
     * @dataProvider validateIban
     */
    public function test_validateIban($iban,$valid){

        $ibanCalculator=new IbanCalculator('180');

        $isValid= $ibanCalculator->validateIban($iban);

        $this->assertEquals($isValid,$valid ,"Validation functions result dont much with the expected one");

    }
}
