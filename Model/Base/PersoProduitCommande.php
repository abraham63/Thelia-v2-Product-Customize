<?php

namespace PersoProduit\Model\Base;

use \Exception;
use \PDO;
use PersoProduit\Model\PersoProduitCommandeQuery as ChildPersoProduitCommandeQuery;
use PersoProduit\Model\Map\PersoProduitCommandeTableMap;
use PersoProduit\Model\Thelia\Model\Order as ChildOrder;
use PersoProduit\Model\Thelia\Model\OrderQuery;
use Propel\Runtime\Propel;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Propel\Runtime\ActiveRecord\ActiveRecordInterface;
use Propel\Runtime\Collection\Collection;
use Propel\Runtime\Connection\ConnectionInterface;
use Propel\Runtime\Exception\BadMethodCallException;
use Propel\Runtime\Exception\PropelException;
use Propel\Runtime\Map\TableMap;
use Propel\Runtime\Parser\AbstractParser;

abstract class PersoProduitCommande implements ActiveRecordInterface
{

    const TABLE_MAP = '\\PersoProduit\\Model\\Map\\PersoProduitCommandeTableMap';
    protected $new = true;
    protected $deleted = false;
    protected $modifiedColumns = array();
    protected $id;
    protected $order_prod_id;
    protected $comment;
    protected $aOrder;
    protected $alreadyInSave = false;

    public function __construct()
    {
    }

    public function isModified()
    {
        return !!$this->modifiedColumns;
    }

    public function isColumnModified($col)
    {
        return $this->modifiedColumns && isset($this->modifiedColumns[$col]);
    }

    public function getModifiedColumns()
    {
        return $this->modifiedColumns ? array_keys($this->modifiedColumns) : [];
    }

    public function isNew()
    {
        return $this->new;
    }

    public function setNew($b)
    {
        $this->new = (Boolean) $b;
    }

    public function isDeleted()
    {
        return $this->deleted;
    }

    public function setDeleted($b)
    {
        $this->deleted = (Boolean) $b;
    }

    public function resetModified($col = null)
    {
        if (null !== $col) {
            if (isset($this->modifiedColumns[$col])) {
                unset($this->modifiedColumns[$col]);
            }
        } else {
            $this->modifiedColumns = array();
        }
    }

    public function hashCode()
    {
        if (null !== $this->getPrimaryKey()) {
            return crc32(serialize($this->getPrimaryKey()));
        }

        return crc32(serialize(clone $this));
    }

    protected function log($msg, $priority = Propel::LOG_INFO)
    {
        return Propel::log(get_class($this) . ': ' . $msg, $priority);
    }

    public function __sleep()
    {
        $this->clearAllReferences();

        return array_keys(get_object_vars($this));
    }

    public function getId()
    {

        return $this->id;
    }

    public function getOrderProdId()
    {

        return $this->order_prod_id;
    }

    public function getComment()
    {

        return $this->comment;
    }

    public function setId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }
        if ($this->id !== $v) {
            $this->id = $v;
            $this->modifiedColumns[PersoProduitCommandeTableMap::ID] = true;
        }
        return $this;
    }

    public function setOrderProdId($v)
    {
        if ($v !== null) {
            $v = (int) $v;
        }
        if ($this->order_prod_id !== $v) {
            $this->order_prod_id = $v;
            $this->modifiedColumns[PersoProduitCommandeTableMap::ORDER_PROD_ID] = true;
        }
        if ($this->aOrder !== null && $this->aOrder->getId() !== $v) {
            $this->aOrder = null;
        }
        return $this;
    } // setOrderId()

    public function setComment($v)
    {
        if ($v !== null) {
            $v =  $v;
        }
        if ($this->comment !== $v) {
            $this->comment = $v;
            $this->modifiedColumns[PersoProduitCommandeTableMap::COMMENT] = true;
        }
        return $this;
    } // setCart()

    public function hydrate($row, $startcol = 0, $rehydrate = false, $indexType = TableMap::TYPE_NUM)
    {
        try {
            $col = $row[TableMap::TYPE_NUM == $indexType ? 0 + $startcol : PersoProduitCommandeTableMap::translateFieldName('Id', TableMap::TYPE_PHPNAME, $indexType)];
            $this->id = (null !== $col) ? (int) $col : null;
            $col = $row[TableMap::TYPE_NUM == $indexType ? 1 + $startcol : PersoProduitCommandeTableMap::translateFieldName('OrderProdId', TableMap::TYPE_PHPNAME, $indexType)];
            $this->order_id = (null !== $col) ? (int) $col : null;
            $col = $row[TableMap::TYPE_NUM == $indexType ? 2 + $startcol : PersoProduitCommandeTableMap::translateFieldName('Comment', TableMap::TYPE_PHPNAME, $indexType)];
            $this->comment = (null !== $col) ? (string) $col : null;
            $this->resetModified();
            $this->setNew(false);
            if ($rehydrate) {
                $this->ensureConsistency();
            }
            return $startcol + 3; // 3 = OrderCartTableMap::NUM_HYDRATE_COLUMNS.
        } catch (Exception $e) {
            throw new PropelException("Error populating \PersoProduit\Model\PersoProduitCommandet object", 0, $e);
        }
    }

    public function ensureConsistency()
    {
        if ($this->aOrder !== null && $this->order_prod_id !== $this->aOrder->getId()) {
            $this->aOrder = null;
        }
    }

    public function delete(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("This object has already been deleted.");
        }
        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitCommandeTableMap::DATABASE_NAME);
        }
        $con->beginTransaction();
        try {
            $deleteQuery = ChildPersoProduitCommandeQuery::create()
                ->filterByPrimaryKey($this->getPrimaryKey());
                $deleteQuery->delete($con);
                $con->commit();
                $this->setDeleted(true);
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    public function save(ConnectionInterface $con = null)
    {
        if ($this->isDeleted()) {
            throw new PropelException("You cannot save an object that has been deleted.");
        }

        if ($con === null) {
            $con = Propel::getServiceContainer()->getWriteConnection(PersoProduitCommandeTableMap::DATABASE_NAME);
        }
        $con->beginTransaction();
        $isInsert = $this->isNew();
        try {
            $affectedRows = $this->doSave($con);
            PersoProduitCommandeTableMap::addInstanceToPool($this);
            $con->commit();
            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    protected function doSave(ConnectionInterface $con)
    {
        $affectedRows = 0; // initialize var to track total num of affected rows
        if (!$this->alreadyInSave) {
            $this->alreadyInSave = true;
            if ($this->aOrder !== null) {
                if ($this->aOrder->isModified() || $this->aOrder->isNew()) {
                    $affectedRows += $this->aOrder->save($con);
                }
                $this->setOrderProd($this->aOrder);
            }
            if ($this->isNew() || $this->isModified()) {
                if ($this->isNew()) {
                    $this->doInsert($con);
                } else {
                    $this->doUpdate($con);
                }
                $affectedRows += 1;
                $this->resetModified();
            }
            $this->alreadyInSave = false;
        }
        return $affectedRows;
    }

    protected function doInsert(ConnectionInterface $con)
    {
        $modifiedColumns = array();
        $index = 0;
        $this->modifiedColumns[PersoProduitCommandeTableMap::ID] = true;
        if (null !== $this->id) {
            throw new PropelException('Cannot insert a value for auto-increment primary key (' . PersoProduitCommandeTableMap::ID . ')');
        }
        if ($this->isColumnModified(PersoProduitCommandeTableMap::ID)) {
            $modifiedColumns[':p' . $index++]  = 'ID';
        }
        if ($this->isColumnModified(PersoProduitCommandeTableMap::ORDER_PROD_ID)) {
            $modifiedColumns[':p' . $index++]  = 'ORDER_PROD_ID';
        }
        if ($this->isColumnModified(PersoProduitCommandeTableMap::COMMENT)) {
            $modifiedColumns[':p' . $index++]  = 'COMMENT';
        }
        $sql = sprintf(
            'INSERT INTO persoProdCmd (%s) VALUES (%s)',
            implode(', ', $modifiedColumns),
            implode(', ', array_keys($modifiedColumns))
        );
        try {
            $stmt = $con->prepare($sql);
            foreach ($modifiedColumns as $identifier => $columnName) {
                switch ($columnName) {
                    case 'ID':
                        $stmt->bindValue($identifier, $this->id, PDO::PARAM_INT);
                        break;
                    case 'ORDER_PROD_ID':
                        $stmt->bindValue($identifier, $this->order_prod_id, PDO::PARAM_INT);
                        break;
                    case 'COMMENT':
                        $stmt->bindValue($identifier, $this->comment, PDO::PARAM_STR);
                        break;
                }
            }
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute INSERT statement [%s]', $sql), 0, $e);
        }
        try {
            $pk = $con->lastInsertId();
        } catch (Exception $e) {
            throw new PropelException('Unable to get autoincrement id.', 0, $e);
        }
        $this->setId($pk);
        $this->setNew(false);
    }

    protected function doUpdate(ConnectionInterface $con)
    {
        $selectCriteria = $this->buildPkeyCriteria();
        $valuesCriteria = $this->buildCriteria();
        return $selectCriteria->doUpdate($valuesCriteria, $con);
    }

    public function buildCriteria()
    {
        $criteria = new Criteria(PersoProduitCommandeTableMap::DATABASE_NAME);
        if ($this->isColumnModified(PersoProduitCommandeTableMap::ID)) $criteria->add(PersoProduitCommandeTableMap::ID, $this->id);
        if ($this->isColumnModified(PersoProduitCommandeTableMap::ORDER_PROD_ID)) $criteria->add(PersoProduitCommandeTableMap::ORDER_PROD_ID, $this->order_prod_id);
        if ($this->isColumnModified(PersoProduitCommandeTableMap::COMMENT)) $criteria->add(PersoProduitCommandeTableMap::COMMENT, $this->comment);
        return $criteria;
    }

    public function buildPkeyCriteria()
    {
        $criteria = new Criteria(PersoProduitCommandeTableMap::DATABASE_NAME);
        $criteria->add(PersoProduitCommandeTableMap::ID, $this->id);
        return $criteria;
    }

    public function getPrimaryKey()
    {
        return $this->getId();
    }

    public function setPrimaryKey($key)
    {
        $this->setId($key);
    }

    public function isPrimaryKeyNull()
    {
        return null === $this->getId();
    }


    public function clear()
    {
        $this->id = null;
        $this->order_prod_id = null;
        $this->comment = null;
        $this->alreadyInSave = false;
        $this->clearAllReferences();
        $this->resetModified();
        $this->setNew(true);
        $this->setDeleted(false);
    }

    public function clearAllReferences($deep = false)
    {
        if ($deep) {
        }
        $this->aOrder = null;
    }

    public function preDelete(ConnectionInterface $con = null)
    {
        return true;
    }

    public function postDelete(ConnectionInterface $con = null)
    {

    }

}
