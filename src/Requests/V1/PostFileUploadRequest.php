<?php

namespace Eightbitsnl\EasiAdfinityPhpClient\Requests\V1;

use Eightbitsnl\EasiAdfinityPhpClient\Requests\BaseRequest;

class PostFileUploadRequest extends BaseRequest
{
    public const HTTP_METHOD = self::HTTP_POST;
    public const URI = "/v1/fileUploadRequest";

    // @todo url params fileName
}

?>
