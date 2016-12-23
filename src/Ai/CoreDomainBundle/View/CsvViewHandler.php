<?php
// CsvViewHandler.php
/**
 * User: aivanov
 * Date: 20.12.16
 * Time: 16:00
 */
namespace Ai\CoreDomainBundle\View;

use FOS\RestBundle\View\{
    View,
    ViewHandler
};
use Symfony\Component\HttpFoundation\{
    Request,
    Response
};

class CsvViewHandler
{
    public function createResponse(ViewHandler $handler, View $view, Request $request, $format)
    {
        if ($format == 'csv') {
            $data = $view->getData();
            //var_dump($data);die;
            $fiveMBs = 5 * 1024 * 1024;
            $fp = fopen("php://temp/maxmemory:$fiveMBs", 'w');
            if (!empty($data)) {
                $item = array_shift($data);
                fputcsv($fp, array_keys($item));
                fputcsv($fp, $item);
            }
            foreach($data as $item) {
                fputcsv($fp, $item);
            }
            rewind($fp);
            $csv = stream_get_contents($fp);

            return new Response($csv, 200, $view->getHeaders());
        }
    }
}