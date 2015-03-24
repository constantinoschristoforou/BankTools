<?php


namespace Qobo\FinancialTools\Loan;

use \DateTime;
/**
 * Loan calculator class
 *
 * @author Weerapat Poosri <vinboxx@gmail.com>
 */
abstract class Loan
{
    /**
     * Is loan was calculated
     * @var boolean
     */
    protected $isCalculated = false;

    /**
     * Interest rate
     * @var int
     */
    protected $interest_rate;

    /**
     * Loan amount
     * @var int
     */
    protected $loan_amount;

    /**
     * Loan term in month(s)
     * @var int
     */
    protected $loan_term;

    /**
     * Monthly payments
     * @var int
     */
    protected $monthly_payments;

    /**
     * Payment plan
     * @var array
     */
    protected $payment_plan;

    /**
     * Total payment
     * @var int
     */
    protected $total_payment;

    /**
     * Total interest
     * @var int
     */
    protected $total_interest;

    /**
     * Loan start timestamp
     * @var int
     */
    protected $loan_start_timestamp;

    /**
     * Sets $interest_rate to a new value
     *
     * @param int $rate an interest rate
     * @return void
     */
    public function setInterestRate($rate)
    {
        $this->interest_rate = is_numeric($rate) ? $rate : 0;
    }

    /**
     * Sets $loan_term to a new value
     *
     * @param int $month a number of months
     * @return void
     */
    public function setLoanTerm($months)
    {
        $this->loan_term = is_numeric($months) ? $months : 0;
    }

    /**
     * Sets $loan_amount to a new value
     *
     * @param int $amount a loan amount
     * @return void
     */
    public function setLoanAmount($amount)
    {
        $this->loan_amount = $amount;//is_numeric($amount) ? $amount : 0;
    }

    /**
     * Sets $loan_start_timestamp by input month and year
     *
     * @param int $month a loan start month
     * @param int $year a loan start year
     * @return void
     */
    public function setLoanStart($date)
    {

        if (empty($date)) {

            $this->loan_start_timestamp =DateTime::createFromFormat('d/m/Y',date('d/m/Y'));

        } else {

            try {

                @date_default_timezone_set('UTC');
                $this->loan_start_timestamp = DateTime::createFromFormat('d/m/Y',$date);

            } catch (Exception $e) {

                //log here

            }

        }


    }



    abstract public function getMonthlyPayments();
    abstract public function getPaymentPlan();
    abstract public function getTotalPayment();
    abstract public function getTotalInterest();
}


