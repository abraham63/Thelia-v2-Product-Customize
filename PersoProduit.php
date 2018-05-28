<?php


namespace PersoProduit;

use PersoProduit\Model\PersoProduitPanierQuery;
use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Install\Database;
use Thelia\Module\BaseModule;

class PersoProduit extends BaseModule
{
    public function postActivation(ConnectionInterface $con = null)
    {
        try {
            PersoProduitPanierQuery::create()->findOne();
        } catch (\Exception $e) {
            $database = new Database($con->getWrappedConnection());
            $database->insertSql(null, array(THELIA_ROOT . '/local/modules/PersoProduit/Config/thelia.sql'));
        }
    }
}
