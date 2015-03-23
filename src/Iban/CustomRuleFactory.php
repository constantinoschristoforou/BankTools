<?php

namespace Qobo\BankTools\Iban;

use IBAN\Rule\RuleFactoryInterface;
use IBAN\Rule\Exception\RuleNotYetImplementedException;

class CustomRuleFactory implements RuleFactoryInterface{

    public static function CY()
    {
        return new CustomRuleFactory('CY');
    }


    public function __construct($localeCode = 'CY'){
            $this->localeCode = $localeCode;
    }

Public function createIbanRule($ibanRuleCodeAndVersion, $instituteIdentification, $bankAccountNumber)
{
    $ibanRuleFilename = 'Rule' . $ibanRuleCodeAndVersion . '.php';
    $ibanRuleFilePath = __DIR__.DIRECTORY_SEPARATOR.'Rule' . DIRECTORY_SEPARATOR . $this->localeCode . DIRECTORY_SEPARATOR . $ibanRuleFilename;

    if (file_exists($ibanRuleFilePath)) {
        $ibanRuleQualifiedClassName = 'Qobo\\BankTools\\Iban\\Rule\\' . $this->localeCode . '\\Rule' . $ibanRuleCodeAndVersion;
        return new $ibanRuleQualifiedClassName($this->localeCode, $instituteIdentification, $bankAccountNumber);

    } else {
        throw new RuleNotYetImplementedException('Rule11' . $ibanRuleFilePath);
    }
}



} 