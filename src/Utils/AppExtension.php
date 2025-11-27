<?php

namespace App\Utils;

use Twig\Attribute\AsTwigFilter;  


class AppExtension
{
    #[AsTwigFilter('json_decode')]
    public function jsonDecode($str) {
        return json_decode($str);
    }


    #[AsTwigFilter('wraped_json')]
    public function wrapedJson($str) {
        $string = '';
        foreach(preg_split("/((\r?\n)|(\r\n?))/", $str) as $line){
            if (str_ends_with($line, "{") || str_ends_with($line, "[")){
                $string .= '<span class="deploy" onclick="unwrap(this)">'.$line.'</span> <div class="wrapper"> <div>';
            }
            else if(str_ends_with($line, "[]") == FALSE && (str_ends_with($line, "]") || str_ends_with($line, "],") || str_ends_with($line, "}") || str_ends_with($line, "},"))){
                $string .= "</div> </div>".$line."<br>";
            }
            else {
                $string .= $line."<br>";
            }
        } 


        return $string;
    }
}