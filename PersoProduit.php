<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

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
