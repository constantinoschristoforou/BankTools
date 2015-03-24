<?php

namespace Qobo\BankTools\Loan;

class LoanCalculator extends Loan
{

    private $thousandsSeparator ;
    private $decimalSeparator;

    function __construct($thousandsSeparator=',',$decimalSeparator='.') {

        $this->thousandsSeparator=$thousandsSeparator;
        $this->decimalSeparator=$decimalSeparator;

    }

    public function validateUserInput()
    {

        $error_messages = array();
        $result = array();

        if (empty($this->loan_amount)) {
            $error_messages['loan_amount'] = 'Please give a loan amount';
        }
        if (!is_numeric($this->loan_term)) {
            $error_messages['loan_term'] = 'Please give the months to pay back';
        }

        if (!is_numeric($this->interest_rate)) {
            $error_messages['interest_rate'] = 'Please give the annual interest rate';
        }
        if (empty($this->loan_start_timestamp)) {

             $error_messages['start_date'] = 'Please give a valid loan start date';

        }

        if (count($error_messages)) {

            $result['status'] = 'ERROR';
            $result['error_messages'] = $error_messages;

        } else {
            $result['status'] = "OK";
        }

        return $result;
    }

    public function getLoanMonthlyPayments()
    {

        $this->loan_amount = str_replace($this->thousandsSeparator, '', $this->loan_amount);

        $monthly_payments = $this->getMonthlyPayments();

        if (is_nan($monthly_payments)) {
            $result['status'] = 'ERROR';
            $result['error_messages'] = array('monthly_payments' => 'The result number is too large');
        } else {
            $result['status'] = 'OK';
            $result['data'] = array(
                'monthly_payments' => $monthly_payments,
                'payment_plan' => $this->getPaymentPlan(),
                'total_payment' => $this->getTotalPayment(),
                'total_interest' => $this->getTotalInterest(),
                'loan_amount' => $this->loan_amount
            );
        }

        return $result;

    }

    /**
     * Get monthly payments
     *
     * @return int the monthly payments
     */
    public function getMonthlyPayments()
    {
        if(!$this->isCalculated) {
            $this->calculateMonthlyPayments();
        }
        return $this->monthly_payments;
    }

    /**
     * Get payment plan
     *
     * @return array the payment plan
     */
    public function getPaymentPlan()
    {
        if(!$this->isCalculated) {
            $this->calculateMonthlyPayments();
        }
        return $this->payment_plan;
    }

    /**
     * Get total payment
     *
     * @return int the total payment
     */
    public function getTotalPayment()
    {
        if(!$this->isCalculated) {
            $this->calculateMonthlyPayments();
        }
        return $this->total_payment;
    }

    /**
     * Get total interest
     *
     * @return int the total interest
     */
    public function getTotalInterest()
    {
        if(!$this->isCalculated) {
            $this->calculateMonthlyPayments();
        }
        return $this->total_interest;
    }

    /**
     * Calculate monthly payments
     *
     * @return void
     */
    private function calculateMonthlyPayments()
    {
        $amount = $this->loan_amount;
        $rate = $this->interest_rate;
        $months = $this->loan_term;

        // 1. Monthly payments
        if($amount > 0 && $rate > 0 && $months > 0) {
            $i = $rate/1200; // Periodic Interest Rate
            $pow_i = pow((1+$i), $months);
            $discount_factor = ($pow_i-1)/($i*$pow_i);
            $this->monthly_payments = $amount/$discount_factor;
        } else {
            $this->monthly_payments = 0;
        }

        // 2. Payment plan
        $payment_plan = array();
        $total_interest = 0;
        $current_balance = $amount;
        $current_date = $this->loan_start_timestamp;
        for($j=0; $j<$months; $j++) {
            $interest = $i * $current_balance;
            $principal = $this->monthly_payments - $interest;
            $current_balance -= $principal;
            $payment_plan[] = array(
                'date' =>  $current_date->format('M, Y'),
                'principal' => $principal,
                'interest' => $interest,
                'balance' => ($current_balance < 0) ? 0 : $current_balance
            );
            $current_date->modify('first day of next month');
            $total_interest += $interest;
        }
        $this->payment_plan = $payment_plan;

        // 3. Total interest
        $this->total_interest = $total_interest;

        // 4. Total payments
        $this->total_payment = $amount+$total_interest;

        // Calculation complete
        $this->isCalculated = true;
    }
}