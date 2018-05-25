<?php

namespace PersoProduit\Model\Base;

use \Exception;
use \PDO;
use PersoProduit\Model\PersoProduitCommande as ChildPersoProduitCommande;
use PersoProduit\Model\PersoProduitCommandeQuery as ChildPersoProduitCommandeQuery;
use PersoProduit\Model\Map\PersoProduitCommandeTableMap;
use PersoProduit\Model\Thelia\Model\Order;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

abstract class PersoProduitCommandeQuery extends ModelCriteria
{

    public function __construct($dbName = 'thelia', $modelName = '\\PersoProduit\\Model\\PersoProduitCommande', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \PersoProduit\Model\PersoProduitCommandeQuery) {
            return $criteria;
        }
        $query = new \PersoProduit\Model\PersoProduitCommandeQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }
        return $query;
    }

    public function filterByOrderProdId($orderProdId = null, $comparison = null)
    {
        if (is_array($orderProdId)) {
            $useMinMax = false;
            if (isset($orderProdId['min'])) {
                $this->addUsingAlias(PersoProduitCommandeTableMap::ORDER_PROD_ID, $orderProdId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($orderProdId['max'])) {
                $this->addUsingAlias(PersoProduitCommandeTableMap::ORDER_PROD_ID, $orderProdId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }
        return $this->addUsingAlias(PersoProduitCommandeTableMap::ORDER_PROD_ID, $orderProdId, $comparison);
    }

     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitCommandeTableMap::DATABASE_NAME);
        }
        $criteria = $this;
        $criteria->setDbName(PersoProduitCommandeTableMap::DATABASE_NAME);
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            $con->beginTransaction();
            PersoProduitCommandeTableMap::removeInstanceFromPool($criteria);
            $affectedRows += ModelCriteria::delete($con);
            PersoProduitCommandeTableMap::clearRelatedInstancePool();
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

}
