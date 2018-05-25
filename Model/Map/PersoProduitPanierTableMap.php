<?php

namespace PersoProduit\Model\Map;

use PersoProduit\Model\PersoProduitPanier;
use PersoProduit\Model\PersoProduitPanierQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\InstancePoolTrait;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\DataFetcher\DataFetcherInterface;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\RelationMap;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Map\TableMapTrait;

class PersoProduitPanierTableMap extends TableMap
{
    use InstancePoolTrait;
    use TableMapTrait;

    const CLASS_NAME = 'PersoProduit.Model.Map.PersoProduitPanierTableMap';
    const DATABASE_NAME = 'thelia';
    const TABLE_NAME = 'PersoProd';
    const OM_CLASS = '\\PersoProduit\\Model\\PersoProduitPanier';
    const CLASS_DEFAULT = 'PersoProduit.Model.PersoProduitPanier';
    const NUM_COLUMNS = 3;
    const NUM_LAZY_LOAD_COLUMNS = 0;
    const NUM_HYDRATE_COLUMNS = 3;
    const ID = 'PersoProd.ID';
    const ITEM_ID = 'PersoProd.ITEM_ID';
    const COMMENT = 'PersoProd.COMMENT';
    const DEFAULT_STRING_FORMAT = 'YAML';

    protected static $fieldNames = array (
        self::TYPE_PHPNAME       => array('Id', 'ItemId', 'Comment', ),
        self::TYPE_STUDLYPHPNAME => array('id', 'itemId', 'comment', ),
        self::TYPE_COLNAME       => array(PersoProduitPanierTableMap::ID, PersoProduitPanierTableMap::ITEM_ID, PersoProduitPanierTableMap::COMMENT, ),
        self::TYPE_RAW_COLNAME   => array('ID', 'ITEM_ID', 'COMMENT', ),
        self::TYPE_FIELDNAME     => array('id', 'item_id', 'comment', ),
        self::TYPE_NUM           => array(0, 1, 2, )
    );
    protected static $fieldKeys = array (
        self::TYPE_PHPNAME       => array('Id' => 0, 'ItemId' => 1, 'Comment' => 2, ),
        self::TYPE_STUDLYPHPNAME => array('id' => 0, 'itemId' => 1, 'comment' => 2, ),
        self::TYPE_COLNAME       => array(PersoProduitPanierTableMap::ID => 0, PersoProduitPanierTableMap::ITEM_ID => 1, PersoProduitPanierTableMap::COMMENT => 2, ),
        self::TYPE_RAW_COLNAME   => array('ID' => 0, 'ITEM_ID' => 1, 'COMMENT' => 2, ),
        self::TYPE_FIELDNAME     => array('id' => 0, 'item_id' => 1, 'comment' => 2, ),
        self::TYPE_NUM           => array(0, 1, 2, )
    );


    public function initialize()
    {
        // attributes
        $this->setName('PersoProd');
        $this->setPhpName('PersoProd');
        $this->setClassName('\\PersoProduit\\Model\\PersoProduitPanier');
        $this->setPackage('PersoProduit.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('ID', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('ITEM_ID', 'ItemId', 'INTEGER', true, null, null);
        $this->addColumn('COMMENT', 'Comment', 'LONGVARCHAR', true, null, null);
    }


    public function buildRelations()
    {
    } // buildRelations()

    public static function getPrimaryKeyHashFromRow($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
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
        return $withPrefix ? PersoProduitPanierTableMap::CLASS_DEFAULT : PersoProduitPanierTableMap::OM_CLASS;
    }

    public static function populateObject($row, $offset = 0, $indexType = TableMap::TYPE_NUM)
    {
        $key = PersoProduitPanierTableMap::getPrimaryKeyHashFromRow($row, $offset, $indexType);
        if (null !== ($obj = PersoProduitPanierTableMap::getInstanceFromPool($key))) {
            $col = $offset + PersoProduitPanierTableMap::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PersoProduitPanierTableMap::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $offset, false, $indexType);
            PersoProduitPanierTableMap::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }

    public static function populateObjects(DataFetcherInterface $dataFetcher)
    {
        $results = array();
        $cls = static::getOMClass(false);
        while ($row = $dataFetcher->fetch()) {
            $key = PersoProduitPanierTableMap::getPrimaryKeyHashFromRow($row, 0, $dataFetcher->getIndexType());
            if (null !== ($obj = PersoProduitPanierTableMap::getInstanceFromPool($key))) {
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PersoProduitPanierTableMap::addInstanceToPool($obj, $key);
            } // if key exists
        }

        return $results;
    }

    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PersoProduitPanierTableMap::ID);
            $criteria->addSelectColumn(PersoProduitPanierTableMap::ITEM_ID);
            $criteria->addSelectColumn(PersoProduitPanierTableMap::COMMENT);
        } else {
            $criteria->addSelectColumn($alias . '.ID');
            $criteria->addSelectColumn($alias . '.ITEM_ID');
            $criteria->addSelectColumn($alias . '.COMMENT');
        }
    }

    public static function getTableMap()
    {
        return Propel::getServiceContainer()->getDatabaseMap(PersoProduitPanierTableMap::DATABASE_NAME)->getTable(PersoProduitPanierTableMap::TABLE_NAME);
    }

    public static function buildTableMap()
    {
      $dbMap = Propel::getServiceContainer()->getDatabaseMap(PersoProduitPanierTableMap::DATABASE_NAME);
      if (!$dbMap->hasTable(PersoProduitPanierTableMap::TABLE_NAME)) {
        $dbMap->addTableObject(new PersoProduitPanierTableMap());
      }
    }

     public static function doDelete($values, ConnectionInterface $con = null)
     {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitPanierTableMap::DATABASE_NAME);
        }
        if ($values instanceof Criteria) {
            $criteria = $values;
        } elseif ($values instanceof \PersoProduitPanier\Model\PersoProduitPanier) { // it's a model object
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PersoProduitPanierTableMap::DATABASE_NAME);
            $criteria->add(PersoProduitPanierTableMap::ID, (array) $values, Criteria::IN);
        }
        $query = PersoProduitPanierQuery::create()->mergeWith($criteria);
        if ($values instanceof Criteria) { PersoProduitPanierTableMap::clearInstancePool();
        } elseif (!is_object($values)) { // it's a primary key, or an array of pks
            foreach ((array) $values as $singleval) { PersoProduitPanierTableMap::removeInstanceFromPool($singleval);
            }
        }
        return $query->delete($con);
    }

    public static function doDeleteAll(ConnectionInterface $con = null)
    {
        return PersoProduitPanierQuery::create()->doDeleteAll($con);
    }

    public static function doInsert($criteria, ConnectionInterface $con = null)
    {
        if (null === $con) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitPanierTableMap::DATABASE_NAME);
        }
        if ($criteria instanceof Criteria) {
            $criteria = clone $criteria; // rename for clarity
        } else {
            $criteria = $criteria->buildCriteria(); // build Criteria from PersoProduitPanier object
        }
        if ($criteria->containsKey(PersoProduitPanierTableMap::ID) && $criteria->keyContainsValue(PersoProduitPanierTableMap::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PersoProduitPanierTableMap::ID.')');
        }
        $query = PersoProduitPanierQuery::create()->mergeWith($criteria);
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
PersoProduitPanierTableMap::buildTableMap();
