<?php



require_once 'IBANGeneratorCY.php';

use IBAN\Validation\IBANValidator;

class IbanCalculator
{

    private $bankCode;
    function __construct($bankCode)
    {

        $this->bankCode = $bankCode;
    }

    public function normalizeBranchCode($branch_code, $digit_num = 5)
    {
        $padding = '';
        for ($i = 0; $i < $digit_num - strlen($branch_code); $i++) {
            $padding .= '0';
        }
        return $padding . $branch_code;
    }

    public function validateIban($iban)
    {
            $ibanValidator = new IBANValidator();

            return $ibanValidator->validate($iban);

    }

    public function generateIban($branch_code, $account_number)
    {
        $ibanGenerator = new IBANGeneratorCY();
        $instituteIdentification = $this->bankCode . $this->normalizeBranchCode($branch_code);
        $generatedIban = $ibanGenerator->generate($instituteIdentification, $account_number);

        return $generatedIban;
    }


    public function validateUserInputGeneration($branch_code,$account_number)
    {

        $error_messages = array();
        $result = array();

        if (empty($branch_code)) {
            $error_messages['branch_code'] = 'Please select a branch';
        }
        if (empty($account_number)) {
            $error_messages['account_number'] = 'Please give your account number';
        }

        if (strlen($account_number) != 16) {
            $error_messages['account_number_limit'] = 'Account number must be 16 characters';
        }

        if (count($error_messages)) {

            $result['status'] = 'ERROR';
            $result['error_messages'] = $error_messages;

        } else {

            $result['status'] = 'OK';

        }

        return $result;

    }

    public function validateUserInputValidation($iban)
    {
        $error_messages = array();
        $result = array();

        if (empty($iban)) {
            $error_messages['iban_number'] = 'Please enter a iban number';
        }

        if (!ctype_alnum($iban)) {
            $error_messages['iban_number_alnum'] = 'Iban number must be alphanumeric';
        }

        if (strlen($iban)!=28) {
            $error_messages['iban_number_size'] = 'Iban number must be 28 letters';
        }

        if (count($error_messages)) {

            $result['status'] = 'ERROR';
            $result['error_messages'] = $error_messages;

        }else{

            $result['status']='OK';
        }

        return $result;

    }


} 