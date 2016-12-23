<?php
// DecoderCsv.php
/**
 * User: aivanov
 * Date: 20.12.16
 * Time: 16:46
 */
namespace Ai\CoreDomainBundle\Decoder;

use FOS\RestBundle\Decoder\DecoderInterface;
use JMS\Serializer\Serializer;

class DecoderCsv implements DecoderInterface
{
    private $serializer;

    public function __construct(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @inheritDoc
     */
    public function decode($data)
    {
        $data = str_getcsv($data, "\n");
        $keys = str_getcsv(array_shift($data));
        foreach($data as &$row) {
            $row = array_combine($keys, str_getcsv($row));
        }
        return $data;
    }
}