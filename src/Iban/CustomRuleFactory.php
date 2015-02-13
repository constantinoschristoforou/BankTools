<?php


include_once 'Rule/CY/Rule000000.php';


use IBAN\Rule\RuleFactory;
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
    $ibanRuleFilePath =  'src' .DIRECTORY_SEPARATOR.'Iban'.DIRECTORY_SEPARATOR.'Rule' . DIRECTORY_SEPARATOR . $this->localeCode . DIRECTORY_SEPARATOR . $ibanRuleFilename;

    if (file_exists($ibanRuleFilePath)) {
        $ibanRuleQualifiedClassName = '\\IBAN\\Rule\\' . $this->localeCode . '\\Rule' . $ibanRuleCodeAndVersion;

        return new $ibanRuleQualifiedClassName($this->localeCode, $instituteIdentification, $bankAccountNumber);

    } else {
        throw new RuleNotYetImplementedException('Rule11' . $ibanRuleFilePath);
    }
}



} 