<?php

namespace PersoProduit\Model\Base;

use \Exception;
use \PDO;
use PersoProduit\Model\PersoProduitPanier as ChildPersoProduitPanier;
use PersoProduit\Model\PersoProduitPanierQuery as ChildPersoProduitPanierQuery;
use PersoProduit\Model\Map\PersoProduitPanierTableMap;
use PersoProduit\Model\Thelia\Model\Order;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\PropelException;

abstract class PersoProduitPanierQuery extends ModelCriteria
{

    public function __construct($dbName = 'thelia', $modelName = '\\PersoProduit\\Model\\PersoProduitPanier', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof \PersoProduit\Model\PersoProduitPanierQuery) {
            return $criteria;
        }
        $query = new \PersoProduit\Model\PersoProduitPanierQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }
        return $query;
    }

    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PersoProduitPanierTableMap::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getReadConnection(PersoProduitPanierTableMap::DATABASE_NAME);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT ID, ORDER_ID, COMMENT FROM PersoProd WHERE ID = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), 0, $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
            $obj = new ChildPersoProduitPanier();
            $obj->hydrate($row);
            PersoProduitPanierTableMap::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    protected function findPkComplex($key, $con)
    {
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($dataFetcher);
    }

    public function findPks($keys, $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getReadConnection($this->getDbName());
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $dataFetcher = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($dataFetcher);
    }

    public function filterByPrimaryKey($key)
    {
        return $this->addUsingAlias(PersoProduitPanierTableMap::ID, $key, Criteria::EQUAL);
    }

    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PersoProduitPanierTableMap::ID, $keys, Criteria::IN);
    }

    public function filterByItemId($itemId = null, $comparison = null)
    {
        if (is_array($itemId)) {
            $useMinMax = false;
            if (isset($itemId['min'])) {
                $this->addUsingAlias(PersoProduitPanierTableMap::ITEM_ID, $itemId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($itemId['max'])) {
                $this->addUsingAlias(PersoProduitPanierTableMap::ITEM_ID, $itemId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PersoProduitPanierTableMap::ITEM_ID, $itemId, $comparison);
    }

    public function doDeleteAll(ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitPanierTableMap::DATABASE_NAME);
        }
        $affectedRows = 0;
        try {
            $con->beginTransaction();
            $affectedRows += parent::doDeleteAll($con);
            PersoProduitPanierTableMap::clearInstancePool();
            PersoProduitPanierTableMap::clearRelatedInstancePool();

            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
        return $affectedRows;
    }

     public function delete(ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitPanierTableMap::DATABASE_NAME);
        }
        $criteria = $this;
        $criteria->setDbName(PersoProduitPanierTableMap::DATABASE_NAME);
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            $con->beginTransaction();
            PersoProduitPanierTableMap::removeInstanceFromPool($criteria);
            $affectedRows += ModelCriteria::delete($con);
            PersoProduitPanierTableMap::clearRelatedInstancePool();
            $con->commit();
            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }
}
