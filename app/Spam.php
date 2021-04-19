<?php


namespace App;


class Spam
{

    public function detect($body)
    {
        $this->detectInvalidKeywords($body);
        return false;
    }

    protected function detectInvalidKeywords($body)
    {
        $invalid_keywords = [
            'yahoo customer support'
        ];

        foreach ($invalid_keywords as $keyword) {
            if (stripos($body, $keyword) !== false) {
                throw(new \Exception('Your reply contain spam'));
            }
        }
    }

}
