<?php

namespace Eightbitsnl\EasiAdfinityPhpClient\Requests\V1;

use Eightbitsnl\EasiAdfinityPhpClient\Requests\BaseRequest;
use Eightbitsnl\EasiAdfinityPhpClient\Traits\Filterable;

class PostAccountingEntries extends BaseRequest
{
    use Filterable;

    public const HTTP_METHOD = self::HTTP_POST;
    public const URI = "/v1/accountingentries";

}

?>
