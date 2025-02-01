<?php

namespace App\Services\Pars\Response;

class SteamCollectionResponse implements ResponseInterface{
    private $data;

    /**
     * set html response
     * @param HtmlDocument $stream
     */
    public function setData($stream) {
        $scriptText = $this->getJsScript($stream);

        $this->data = $this->getPeriodData($scriptText);

        return $this;
    }

    private function getJsScript($stream) {
        $jsScript = $stream->find('script[type="text/javascript"]')[22];

	    $scriptText = $jsScript->innertext;

        return $scriptText;
    }

    /**
     * Formating response stirng
     */
    private function getPeriodData(string $text) {
        $line1 = explode('var g_rgFilterData = ', $text)[1];
	    
        $arrLine = explode('var g_unFilterApp', $line1)[0];

        $strData = substr($arrLine, 0, (strlen($arrLine) - 3));
        
        $json = json_decode($strData, true);

        return $json;
    }

    /**
     * Get all collections name
     * return json
     */
    public function parsJson(string $response = "")
    {
        return $this->data["730_ItemSet"]["tags"];
    }
}