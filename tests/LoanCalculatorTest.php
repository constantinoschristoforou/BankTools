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
            array('100', '10', '5', '13/02/2015', 'OK'),
            array('1000', '20', '7', '', 'OK'),
            array('10000', '30', '10', '', 'OK'),
        );
    }


    public function loanCalculator()
    {
        return array(
            array('1', '10', '5', '13/02/2015'),
            array('1000', '20', '7', ''),
            array('10000', '30', '10', ''),
        );
    }

    /**
     *
     * @dataProvider userInputLoanCalculator
     */
    public function test_validateUserInput($loanAmount, $loanMonths, $loanRate, $startDate, $valid)
    {
        $loanCalculator = new LoanCalculator();
        $loanCalculator->setLoanAmount($loanAmount);
        $loanCalculator->setLoanTerm($loanMonths);
        $loanCalculator->setInterestRate($loanRate);
        $loanCalculator->setLoanStart($startDate);

        $result = $loanCalculator->validateUserInput();

        $this->assertEquals($result['status'], $valid, "Validation function result dont much with the expected one");

    }


    /**
     *
     * @dataProvider loanCalculator
     */
    public function test_loadCalculation($loanAmount, $loanMonths, $loanRate, $startDate)
    {
        $loanCalculator = new LoanCalculator();
        $loanCalculator->setLoanAmount($loanAmount);
        $loanCalculator->setLoanTerm($loanMonths);
        $loanCalculator->setInterestRate($loanRate);
        $loanCalculator->setLoanStart($startDate);

        $result = $loanCalculator->getLoanMonthlyPayments();

        $this->assertEquals('OK', $result['status'], 'Loan calculator error');
        $this->assertGreaterThan(0, count($result['data']));


    }
}