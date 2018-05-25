<?php

namespace PersoProduit\Model\Map;

use PersoProduit\Model\PersoProduitCommande;
use PersoProduit\Model\PersoProduitCommandeQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;


class PersoProduitCommandeTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    const CLASS_NAME = 'PersoProduit.Model.Map.PersoProduitCommandeTableMap';
    const DATABASE_NAME = 'thelia';
    const TABLE_NAME = 'persoProdCmd';
    const OM_CLASS = '\\PersoProduit\\Model\\PersoProduitCommande';
    const CLASS_DEFAULT = 'PersoProduit.Model.PersoProduitCommande';
    const NUM_COLUMNS = 3;
    const NUM_LAZY_LOAD_COLUMNS = 0;
    const NUM_HYDRATE_COLUMNS = 3;
    const ID = 'persoProdCmd.ID';
    const ORDER_PROD_ID = 'persoProdCmd.ORDER_PROD_ID';
    const COMMENT = 'persoProdCmd.COMMENT';
    const DEFAULT_STRING_FORMAT = 'YAML';

    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'OrderProdId', 'Comment', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'orderProdId', 'comment', ),
        self::TYPE_COLNAME       => array(PersoProduitCommandeTableMap::ID, PersoProduitCommandeTableMap::ORDER_PROD_ID, PersoProduitCommandeTableMap::COMMENT, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'ORDER_PROD_ID', 'COMMENT', ),
        self::TYPE_FIELDNAME     => array('id', 'order_prod_id', 'COMMENT', ),
        self::TYPE_NUM           => array(0, 1, 2, )
    );
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'OrderProdId' => 1, 'Comment' => 2, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'orderProdId' => 1, 'comment' => 2, ),
        self::TYPE_COLNAME       => array(PersoProduitCommandeTableMap::ID => 0, PersoProduitCommandeTableMap::ORDER_PROD_ID => 1, PersoProduitCommandeTableMap::COMMENT => 2, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'ORDER_PROD_ID' => 1, 'COMMENT' => 2, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'order_prod_id' => 1, 'comment' => 2, ),
        self::TYPE_NUM           => array(0, 1, 2, )
    );

    public function initialize()
    {
        $this->setName('persoProdCmd');
        $this->setPhpName('persoProdCmd');
        $this->setClassName('\\PersoProduit\\Model\\PersoProduitCommande');
        $this->setPackage('PersoProduit.Model');
        $this->setUseIdGenerator(true);
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('ORDER_PROD_ID', 'OrderProdId', 'INTEGER', true, null, null);
        $this->addColumn('COMMENT', 'Comment', 'LONGVARCHAR', true, null, null);
    } // initialize()


    public function buildRelations()
    {

    } // buildRelations()


    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        // If the PK cannot be derived from the row, return NULL.
        if ($row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)] === null) {
            return null;
        }

        return (string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
    }

    public static function getPrimaryKeyFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
            return (int) $row[
                            $indexType == TableMap::TYPE_NUM
                            ? 0 + $offset
                            : self::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)
                        ];
    }

    public static function getOMClass($withPrefix = true)
    {
        return $withPrefix ? PersoProduitCommandeTableMap::CLASS_DEFAULT : PersoProduitCommandeTableMap::OM_CLASS;
    }

    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PersoProduitCommandeTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PersoProduitCommandeTableMap::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $offset, true); // rehydrate
            $col = $offset + PersoProduitCommandeTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PersoProduitCommandeTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PersoProduitCommandeTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    public static function populateObjects(DataFetcherInterface $dataFetcher)
      {
          $results = array();
          $cls = static::getOMClass(false);
          while ($row = $dataFetcher->fetch()) {
              $key = PersoProduitCommandeTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
              if (null !== ($obj = PersoProduitCommandeTableMap::getInstanceFromPool($key))) {
                  $results[] = $obj;
              } else {
                  $obj = new $cls();
                  $obj->hydrate($row);
                  $results[] = $obj;
                  PersoProduitCommandeTableMap::addInstanceToPool($obj, $key);
              }
          }
          return $results;
      }

    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PersoProduitCommandeTableMap::ID);
            $criteria->addSelectColumn(PersoProduitCommandeTableMap::ORDER_PROD_ID);
            $criteria->addSelectColumn(PersoProduitCommandeTableMap::COMMENT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.ORDER_PROD_ID');
            $criteria->addSelectColumn($alias . '.COMMENT');
        }
    }

    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(PersoProduitCommandeTableMap::DATABASE_NAME)->getTable(PersoProduitCommandeTableMap::TABLE_NAME);
    }

    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(PersoProduitCommandeTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(PersoProduitCommandeTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new PersoProduitCommandeTableMap());
      }
    }

     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitCommandeTableMap::DATABASE_NAME);
        }
        if ($values instanceof Criteria) {
            $criteria = $values;
        } elseif ($values instanceof \PersoProduit\Model\PersoProduitCommande) { // it's a model object
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PersoProduitCommandeTableMap::DATABASE_NAME);
            $criteria->add(PersoProduitCommandeTableMap::ID, (array) $values, Criteria::IN);
        }
        $query = PersoProduitCommandeQuery::create()->mergeWith($criteria);
        if ($values instanceof Criteria) { PersoProduitCommandeTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { PersoProduitCommandeTableMap::removeInstanceFromPool($singleval);
            }
        }
        return $query->delete($con);
    }

    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PersoProduitCommandeQuery::create()->doDeleteAll($con);
    }

    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitCommandeTableMap::DATABASE_NAME);
        }
        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from OrderCart object
        }
        if ($criteria->containsKey(PersoProduitCommandeTableMap::ID) && $criteria->keyContainsValue(PersoProduitCommandeTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PersoProduitCommandeTableMap::ID.')');
        }
        $query = PersoProduitCommandeQuery::create()->mergeWith($criteria);
        try {
            $con->beginTransaction();
            $pk = $query->doInsert($con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
        return $pk;
    }
}
PersoProduitCommandeTableMap::buildTableMap();
