<?php

require_once (__DIR__."/../../../config.php");
require_once (__DIR__."/../config.php");
require_once (__DIR__."/../../../modules/utilities/functions.php");
require_once (__DIR__."/../../../modules/utilities/safemysql.class.php");




/**
 * @param string $id TermID
 * @return array
 */
function termGetByID($id = false) {

    global $config;

    if (!$id) {

        $return["meta"]["requestStatus"] = "error";
        $return["errors"] = array();
        $errorarray["status"] = "422";
        $errorarray["code"] = "1";
        $errorarray["title"] = "Missing request parameter";
        $errorarray["detail"] = "Required parameter of the request are missing"; //TODO: Description
        array_push($return["errors"], $errorarray);

        return $return;

    } else {

        $opts = array(
            'host'	=> $config["platform"]["sql"]["access"]["host"],
            'user'	=> $config["platform"]["sql"]["access"]["user"],
            'pass'	=> $config["platform"]["sql"]["access"]["passwd"],
            'db'	=> $config["platform"]["sql"]["db"]
        );


        try {

            $db = new SafeMySQL($opts);

        } catch (exception $e) {

            $return["meta"]["requestStatus"] = "error";
            $return["errors"] = array();
            $errorarray["status"] = "503";
            $errorarray["code"] = "1";
            $errorarray["title"] = "Database connection error";
            $errorarray["detail"] = "Connecting to database failed"; //TODO: Description
            array_push($return["errors"], $errorarray);
            return $return;

        }

        $item = $db->getRow("SELECT * FROM ".$config["platform"]["sql"]["tbl"]["Term"]." WHERE TermID=?i",$id);

        if ($item) {

            $return["meta"]["requestStatus"] = "success";
            $termDataObj["data"] = termGetDataObject($item, $db);
            $return = array_replace_recursive($return, $termDataObj);

        } else {

            $return["meta"]["requestStatus"] = "error";
            $return["errors"] = array();
            $errorarray["status"] = "404";
            $errorarray["code"] = "1";
            $errorarray["title"] = "Term not found";
            $errorarray["detail"] = "Term with the given ID was not found in database"; //TODO: Description
            array_push($return["errors"], $errorarray);

        }

        return $return;

    }
}

/**
 * @param string $wikidataID TermID
 * @return array
 */
function termGetByWikidataID($wikidataID = false) {

    global $config;

    if (!$wikidataID) {

        $return["meta"]["requestStatus"] = "error";
        $return["errors"] = array();
        $errorarray["status"] = "422";
        $errorarray["code"] = "1";
        $errorarray["title"] = "Missing request parameter";
        $errorarray["detail"] = "Required parameter of the request are missing"; //TODO: Description
        array_push($return["errors"], $errorarray);

        return $return;

    } else {

        $opts = array(
            'host'	=> $config["platform"]["sql"]["access"]["host"],
            'user'	=> $config["platform"]["sql"]["access"]["user"],
            'pass'	=> $config["platform"]["sql"]["access"]["passwd"],
            'db'	=> $config["platform"]["sql"]["db"]
        );


        try {

            $db = new SafeMySQL($opts);

        } catch (exception $e) {

            $return["meta"]["requestStatus"] = "error";
            $return["errors"] = array();
            $errorarray["status"] = "503";
            $errorarray["code"] = "1";
            $errorarray["title"] = "Database connection error";
            $errorarray["detail"] = "Connecting to database failed"; //TODO: Description
            array_push($return["errors"], $errorarray);
            return $return;

        }

        $item = $db->getRow("SELECT * FROM ".$config["platform"]["sql"]["tbl"]["Term"]." WHERE TermWikidataID=?s",$wikidataID);

        if ($item) {

            $return["meta"]["requestStatus"] = "success";
            $termDataObj["data"] = termGetDataObject($item, $db);
            $return = array_replace_recursive($return, $termDataObj);

        } else {

            $return["meta"]["requestStatus"] = "error";
            $return["errors"] = array();
            $errorarray["status"] = "404";
            $errorarray["code"] = "1";
            $errorarray["title"] = "Term not found";
            $errorarray["detail"] = "Term with the given ID was not found in database"; //TODO: Description
            array_push($return["errors"], $errorarray);

        }

        return $return;

    }
}


function termGetDataObject($item = false, $db = false) {

    global $config;

    if ((is_array($item)) && $db) {

        //$return["meta"]["requestStatus"] = "success";
        $return["type"] = "term";
        $return["id"] = $item["TermID"];
        $return["attributes"]["type"] = $item["TermType"];
        $return["attributes"]["wikidataID"] = $item["TermWikidataID"];
        $return["attributes"]["label"] = $item["TermLabel"];
        $return["attributes"]["labelAlternative"] = json_decode($item["TermLabelAlternative"],true);
        $return["attributes"]["abstract"] = $item["TermAbstract"];
        $return["attributes"]["thumbnailURI"] = $item["TermThumbnailURI"];
        $return["attributes"]["thumbnailCreator"] = $item["TermThumbnailCreator"];
        $return["attributes"]["thumbnailLicense"] = $item["TermThumbnailLicense"];
        $return["attributes"]["sourceURI"] = $item["TermSourceURI"];
        $return["attributes"]["embedURI"] = $item["TermEmbedURI"];
        $return["attributes"]["additionalInformation"] = json_decode($item["TermAdditionalInformation"],true);
        $return["attributes"]["lastChanged"] = $item["TermLastChanged"];
        $return["links"]["self"] = $config["dir"]["api"]."/".$return["type"]."/".$return["id"];
        $return["relationships"]["media"]["links"]["self"] = ""; //TODO: Link - "self"?

    } else {

        $return = false;

    }

    return $return;
}


function termSearch($parameter, $db = false) {

    global $config;

    $outputLimit = 25;

    if (!$db) {

        $opts = array(
            'host'	=> $config["platform"]["sql"]["access"]["host"],
            'user'	=> $config["platform"]["sql"]["access"]["user"],
            'pass'	=> $config["platform"]["sql"]["access"]["passwd"],
            'db'	=> $config["platform"]["sql"]["db"]
        );

        try {

            $db = new SafeMySQL($opts);

        } catch (exception $e) {

            $return["meta"]["requestStatus"] = "error";
            $return["errors"] = array();
            $errorarray["status"] = "503";
            $errorarray["code"] = "1";
            $errorarray["title"] = "Database connection error";
            $errorarray["detail"] = "Connecting to database failed"; //TODO: Description
            array_push($return["errors"], $errorarray);
            return $return;

        }

    }

    $allowedFields = ["label", "type", "wikidataID"];

    $filteredParameters = array_filter(
        $parameter,
        function ($key) use ($allowedFields) {
            return in_array($key, $allowedFields);
        },
        ARRAY_FILTER_USE_KEY
    );




    /************ VALIDATION START ************/

    if (array_key_exists("label", $filteredParameters)) {

        if (is_array($filteredParameters["label"])) {

            foreach ($filteredParameters["label"] as $tmpNameID) {

                if (mb_strlen($tmpNameID, "UTF-8") < 3) {

                    $return["meta"]["requestStatus"] = "error";
                    $errorarray["status"] = "400";
                    $errorarray["code"] = "1";
                    $errorarray["title"] = "label too short";
                    $errorarray["detail"] = "Searching for label needs at least 3 characters."; //  TODO: Description
                    $return["errors"][] = $errorarray;

                }

            }

        } else {

            if (mb_strlen($filteredParameters["label"], "UTF-8") < 3) {

                $return["meta"]["requestStatus"] = "error";
                $errorarray["status"] = "400";
                $errorarray["code"] = "1";
                $errorarray["title"] = "name too short";
                $errorarray["detail"] = "Searching for label needs at least 3 characters."; //  TODO: Description
                $return["errors"][] = $errorarray;

            }
        }
    }



    if (array_key_exists("type", $filteredParameters) && (mb_strlen($filteredParameters["type"], "UTF-8") < 2)) {

        $return["meta"]["requestStatus"] = "error";
        $errorarray["status"] = "400";
        $errorarray["code"] = "2";
        $errorarray["title"] = "type too short";
        $errorarray["detail"] = "Searching for type needs at least 2 characters."; //  TODO: Description
        $return["errors"][] = $errorarray;

    }



    if (array_key_exists("wikidataID", $filteredParameters) && (!preg_match("/(Q|P)\d+/i", $filteredParameters["wikidataID"]))) {

        $return["meta"]["requestStatus"] = "error";
        $errorarray["status"] = "400";
        $errorarray["code"] = "2";
        $errorarray["title"] = "wrong wikidataID";
        $errorarray["detail"] = "wikidataID doesn't match the pattern."; //  TODO: Description
        $return["errors"][] = $errorarray;

    }


    /************ VALIDATION END ************/





    if ($return["meta"]["requestStatus"] == "error") {

        return $return;

    }

    $query = "SELECT * FROM ".$config["platform"]["sql"]["tbl"]["Term"];

    $conditions = array();

    foreach ($filteredParameters as $k=>$para) {
        if ($k == "label") {
            if (is_array($para)) {

                $tmpStringArray = array();

                foreach ($para as $tmppara) {

                    $tmpStringArray[] = $db->parse("((MATCH(TermLabel, TermLabelAlternative, TermAbstract) AGAINST (?s IN BOOLEAN MODE)) OR (TermLabel LIKE ?s))", "*" . $tmppara . "*", "%" . $tmppara . "%");
                }

                $tmpStringArray = " (" . implode(" OR ", $tmpStringArray) . ")";
                $conditions[] = $tmpStringArray;

            } else {

                $conditions[] = $db->parse("(MATCH(TermLabel, TermLabelAlternative, TermAbstract) AGAINST (?s IN BOOLEAN MODE) OR (TermLabel LIKE ?s))", "*" . $para . "*", "%" . $para . "%");

            }
        }

        if ($k == "type") {

            $conditions[] = $db->parse("TermType = ?s", $para);

        }

        if ($k == "wikidataID") {

            $conditions[] = $db->parse("TermWikidataID = ?s", $para);

        }

    }


    if (count($conditions) > 0) {

        $query .= " WHERE ".implode(" AND ",$conditions);


        $totalCount = $db->getAll($query);

        $query .= " LIMIT ";

        if ($parameter["page"]) {

            $query .= ($parameter["page"]-1)*$outputLimit.",";

        } else {

            $parameter["page"] = 1;

        }

        $query .= $outputLimit;


        //echo $db->parse($query);
        $findings = $db->getAll($query);

        $return["meta"]["requestStatus"] = "success";
        $return["meta"]["page"] = $parameter["page"];
        $return["meta"]["pageTotal"] = ceil(count($totalCount)/$outputLimit);

        if (!$return["data"]) {
            $return["data"] = array();
        }

        foreach ($findings as $finding) {
            //print_r($finding);
            array_push($return["data"], TermGetDataObject($finding,$db));
        }

    } else {

        $return["meta"]["requestStatus"] = "error";
        $return["errors"] = array();
        $errorarray["status"] = "404";
        $errorarray["code"] = "1";
        $errorarray["title"] = "Not enough parameters";
        $errorarray["detail"] = "Not enough parameters"; //TODO: Description
        array_push($return["errors"], $errorarray);

    }

    if (!array_key_exists("data", $return)) {
        $return["data"] = array();
    }


    $return["links"]["self"] = $config["dir"]["api"]."/search/terms?".getURLParameterFromArray($filteredParameters);

    return $return;



}


function termAdd($item, $db = false) {

    global $config;

    if (!$db) {

        $opts = array(
            'host'	=> $config["platform"]["sql"]["access"]["host"],
            'user'	=> $config["platform"]["sql"]["access"]["user"],
            'pass'	=> $config["platform"]["sql"]["access"]["passwd"],
            'db'	=> $config["platform"]["sql"]["db"]
        );

        try {

            $db = new SafeMySQL($opts);

        } catch (exception $e) {

            $return["meta"]["requestStatus"] = "error";
            $return["errors"] = array();
            $errorarray["status"] = "503";
            $errorarray["code"] = "2";
            $errorarray["title"] = "Database connection error";
            $errorarray["detail"] = "Connecting to platform database failed";
            array_push($return["errors"], $errorarray);
            return $return;

        }

    }

    if (!$item["type"]) {
        $errorarray["status"] = "422";
        $errorarray["code"] = "1";
        $errorarray["title"] = "Type is missing";
        $errorarray["label"] = "type";
        $errorarray["detail"] = "Required parameter of the request is missing";
        $return["errors"][] = $errorarray;
    }

    if ((!$item["id"]) || (!preg_match("/(Q|P)\d+/i", $item["id"]))) {
        $errorarray["status"] = "422";
        $errorarray["code"] = "1";
        $errorarray["title"] = "ID seems to be wrong or missing";
        $errorarray["label"] = "id";
        $errorarray["detail"] = "Required parameter of the request is missing";
        $return["errors"][] = $errorarray;
    }

    if ((!$item["label"]) || strlen($item["label"]) < 3) {
        $errorarray["status"] = "422";
        $errorarray["code"] = "1";
        $errorarray["title"] = "Label is missing or < 3";
        $errorarray["label"] = "label";
        $errorarray["detail"] = "Required parameter of the request is missing";
        $return["errors"][] = $errorarray;
    }

    if ((!$item["abstract"]) || strlen($item["abstract"]) < 5) {
        $errorarray["status"] = "422";
        $errorarray["code"] = "1";
        $errorarray["title"] = "Abstract is missing or <5";
        $errorarray["label"] = "abstract";
        $errorarray["detail"] = "Required parameter of the request is missing";
        $return["errors"][] = $errorarray;
    }

    if ((!$item["sourceuri"]) || strlen($item["sourceuri"]) < 5) {
        $errorarray["status"] = "422";
        $errorarray["code"] = "1";
        $errorarray["title"] = "Source URI is missing or too short";
        $errorarray["label"] = "sourceuri";
        $errorarray["detail"] = "Required parameter of the request is missing";
        $return["errors"][] = $errorarray;
    }

    if ($return["errors"]) {
        $return["meta"]["requestStatus"] = "error";
        return $return;
    } else {


        $itemTmp = $db->getRow("SELECT TermWikidataID FROM ?n WHERE TermWikidataID=?s",$config["platform"]["sql"]["tbl"]["Term"],$item["id"]);

        if ($itemTmp) {
            $return["meta"]["requestStatus"] = "error";
            $errorarray["status"] = "422"; //todo
            $errorarray["code"] = "2";
            $errorarray["title"] = "An item with same WikidataID already exists in Database";
            $errorarray["label"] = "error_info";
            $errorarray["detail"] = "Item already exists in Database";
            $return["errors"][] = $errorarray;
            return $return;

        } else {

            try {

                $labelAlternative = array();
                if (is_array($item["labelAlternative"])) {
                    foreach ($item["labelAlternative"] as $v) {
                        if ($v) {
                            $labelAlternative[] = $v;
                        }
                    }
                }


                $db->query("INSERT INTO ?n SET ".
                    "TermType=?s, ".
                    "TermWikidataID=?s, ".
                    "TermLabel=?s, ".
                    "TermLabelAlternative=?s, ".
                    "TermAbstract=?s, ".
                    "TermThumbnailURI=?s, ".
                    "TermThumbnailCreator=?s, ".
                    "TermThumbnailLicense=?s, ".
                    "TermSourceURI=?s, ".
                    "TermEmbedURI=?s, ".
                    "TermAdditionalInformation=?s",

                    $config["platform"]["sql"]["tbl"]["Term"],
                    $item["type"],
                    $item["id"],
                    $item["label"],
                    (is_array($labelAlternative) ? json_encode($labelAlternative, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : "[".$item["labelAlternative"]."]"),
                    $item["abstract"],
                    $item["thumbnailuri"],
                    $item["thumbnailcreator"],
                    $item["thumbnaillicense"],
                    $item["sourceuri"],
                    $item["embeduri"],
                    (is_array($item["additionalinformation"]) ? json_encode($item["additionalinformation"],JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : $item["additionalinformation"])
                );
                $return["meta"]["requestStatus"] = "success";
                $return["meta"]["itemID"] = $db->insertId();

            } catch (exception $e) {

                $return["meta"]["requestStatus"] = "error";
                $errorarray["status"] = "422"; //todo
                $errorarray["code"] = "2";
                $errorarray["title"] = "Add to database failed";
                $errorarray["label"] = "error_info";
                $errorarray["detail"] = $e->getMessage();
                $return["errors"][] = $errorarray;

            }

        }
    }

    return $return;

}

?>
