<?php
/**
 * Iban
 *
 * @author      Jan Schaedlich <schaedlich.jan@gmail.com>
 * @copyright   2013 Jan Schaedlich
 * @link        https://github.com/jschaedl/Iban
 *
 * MIT LICENSE
 */


namespace Qobo\BankTools\Iban;

use IBAN\Validation\IBANValidator;
use IBAN\Generation\IBANGenerator;
use IBAN\Rule\RuleFactory;
use IBAN\Rule\RuleFactoryInterface;
use Bav\Bav;



class IBANGeneratorCY extends IBANGenerator
{    	
    public function __construct()
    {
        parent::__construct(CustomRuleFactory::CY());
    }
}
