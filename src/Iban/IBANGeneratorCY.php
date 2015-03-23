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

use IBAN\Generation\IBANGenerator;

class IBANGeneratorCY extends IBANGenerator
{    	
    public function __construct()
    {
        parent::__construct(CustomRuleFactory::CY());
    }
}
