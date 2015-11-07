<?php

/**
 * @author Daniel Sampedro Bello <darthdaniel85@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2015 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @version $Id$
 * @since 4.0
 * @package Wsat
 */
Prado::using("System.Wsat.TWsatBaseGenerator");

class TWsatScaffoldingGenerator extends TWsatBaseGenerator
{

    /**
     * Const View Types for generation
     */
    const LIST_TYPE = 0;
    const ADD_TYPE = 1;
    const SHOW_TYPE = 2;

    /**
     * Bootstrap option
     */
    private $_bootstrap;

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Generates CRUD Operations for a single DB table
     * @param type $tableName
     */
    public function generateCRUD($tableName)
    {
        $this->generate($tableName, self::ADD_TYPE);
        $this->generate($tableName, self::LIST_TYPE);
        $this->generate($tableName, self::SHOW_TYPE);
    }

    //---------------------------------------------------------------------
    // <editor-fold defaultstate="collapsed" desc="Page Generation">
    public function generate($tableName, $viewType)
    {
        switch ($viewType)
        {
            default:
            case self::LIST_TYPE:
                $unitName = "list" . ucfirst($tableName);
                break;

            case self::ADD_TYPE:
                $unitName = "add" . ucfirst($tableName);
                break;

            case self::SHOW_TYPE:
                $unitName = "show" . ucfirst($tableName);
                break;
        }

        $class = $this->generateClass($unitName);
        $outputClass = $this->_opFile . DIRECTORY_SEPARATOR . $unitName . ".php";
        file_put_contents($outputClass, $class);

        $outputPage = $this->_opFile . DIRECTORY_SEPARATOR . $unitName . ".page";
        $page = $this->generatePage($tableName, $viewType);
        file_put_contents($outputPage, $page);
    }

    private function generatePage($tableName, $type, $tContentId = "Content")
    {
        $pageContent = $this->getPageContent($tableName, $type);
        return <<<EOD
<com:TContent ID="$tContentId">   
                     
       $pageContent
               
</com:TContent>
EOD;
    }

    private function getPageContent($tableName, $type)
    {
        $code = "";
        $tableInfo = $this->_dbMetaData->getTableInfo($tableName);
        switch ($type)
        {
            case self::LIST_TYPE:
                break;
            case self::ADD_TYPE:
                foreach ($tableInfo->getColumns() as $colField => $colMetadata)
                {
                    if (!$colMetadata->IsPrimaryKey && !$colMetadata->IsForeignKey)
                    {
                        $code .= $this->generateControl($colMetadata);
                        $code .= $this->generateValidators($colMetadata);
                        $code .= "\n";
                    }
                }
                foreach ($tableInfo->getForeignKeys() as $colField => $colMetadata)
                {
                    $colField = $this->eq($colMetadata["table"]);
                    $code .= "\t<com:TTextBox ID=$colField />\n";
                    $code .= "\n";
                    //  TWsatBaseGenerator::pr($tableInfo);
                }
                $code .= "\t<com:TButton Text=\"Accept\" />\n";

            case self::SHOW_TYPE:
                break;
        }
        return $code;
    }

    private function generateControl($colMetadata)
    {
        $controlType = "TTextBox";
        switch ($colMetadata->DbType)
        {
            
        }
        $controlId = $colMetadata->ColumnId;
        return "\t<com:$controlType ID=\"$controlId\" />\n";
    }

    private function generateValidators($colMetadata)
    {
        $controlId = $colMetadata->ColumnId;
        $code = "";
        if (!$colMetadata->AllowNull)
        {
            $code .= "\t<com:TRequiredFieldValidator ControlToValidate=$controlId ValidationGroup=\"addGroup\" Text=\"Field $controlId is required.\" Display=\"Dynamic\" />\n";
        }
        return $code;
    }

// </editor-fold>
    //---------------------------------------------------------------------
    // <editor-fold defaultstate="collapsed" desc="Code Behind Generation">
    private function generateClass($classname)
    {
        $date = date('Y-m-d h:i:s');
        $env_user = getenv("username");
        return <<<EOD
<?php
/**
 * Auto generated by PRADO - WSAT on $date.
 * @author $env_user
 */
class $classname extends TPage
{

}
EOD;
    }

// </editor-fold>
}
