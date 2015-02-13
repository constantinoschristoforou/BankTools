<?php
/**
 * Test Loan Calculator Functionality
 *
 */



include_once 'src/Loan/LoanCalculator.php';

class EuroBanckLoanCalculatorTest extends PHPUnit_Framework_TestCase
{


    /*
     * Dataprovider for user input of Loan calculation
     *
     * Branch code must be not empty
     * Account number must be 16 letters
     */

    public function userInputLoanCalculator()
    {
        return array(
            array('100', '10', '5', '', 'OK'),
            array('1000', '20', '7', '', 'OK'),
            array('10000', '30', '10', '', 'OK'),
        );
    }


    public function loanCalculator()
    {
        return array(
            array('1', '10', '5', '5/05/2005'),
            array('1000', '20', '7', ''),
            array('10000', '30', '10', ''),
        );
    }

    /**
     *
     * @dataProvider userInputLoanCalculator
     */
    public function test_validateUserInput($loan_amount, $loan_months, $loan_rate, $start_date, $valid)
    {

        $loanCalculator = new LoanCalculator();
        $result = $loanCalculator->validateUserInput($loan_amount, $loan_months, $loan_rate, $start_date);

        $this->assertEquals($result['status'], $valid, "Validation function result dont much with the expected one");

    }


    /**
     *
     * @dataProvider loanCalculator
     */
    public function test_loadCalculation($loan_amount, $loan_months, $loan_rate, $start_date)
    {

        $loanCalculator = new LoanCalculator();
        $result = $loanCalculator->getLoanMonthlyPayments($loan_amount, $loan_months, $loan_rate, $start_date);


        $this->assertEquals('OK', $result['status'], 'Loan calculator error');
        $this->assertGreaterThan(0, count($result['data']));


    }
}