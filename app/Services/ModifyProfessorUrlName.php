<?php
namespace Judge\Services;
use Judge\Database\DB;

/**
 * Created by PhpStorm.
 * User: antony
 * Date: 7/29/16
 * Time: 12:13 AM
 */

class ModifyProfessorUrlName
{
    public function InvertNamesToUrl()
    {
        $db = new DB();
        $errorMessage = "";

        $professors = $db->getAllProfessors();

        foreach ($professors as $professor)
        {
            $name = $professor['name'];
            $name = explode(" ", $name);
            $name = implode("-", $name);

            $result = $db->updateProfessorUrlByID($professor['id'], $name);

            if ($result == 0) {
                $errorMessage .= "Error with id " . $professor['id'] . "\n";
            }
        }

        var_dump($errorMessage);
    }

    public function removeLastDash()
    {
        $db = new DB();
        $errorMessage = "";

        $professors = $db->getAllProfessors();

        foreach ($professors as $professor)
        {
            if ($professor['urlName'][strlen($professor['urlName'])-1] === '-') {
                $newUrlName = substr($professor['urlName'], 0, -1);
                var_dump($newUrlName);

                $result = $db->updateProfessorUrlByID($professor['id'], $newUrlName);

                if ($result == 0) {
                    $errorMessage .= "Error with id " . $professor['id'] . "\n";
                }
            }
        }

        var_dump($errorMessage);
    }
}