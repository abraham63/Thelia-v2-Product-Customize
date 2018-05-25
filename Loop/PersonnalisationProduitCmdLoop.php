<?php

namespace PersoProduit\Loop;

use PersoProduit\Model\PersoProduitCommandeQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class PersonnalisationProduitCmdLoop extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('order_prod_id', null, true)
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $PersoProduitPanierQuery = PersoProduitCommandeQuery::create()->filterByOrderProdId($this->getOrderProdId());

        return $PersoProduitPanierQuery;
    }

    /**
     * @param LoopResult $loopResult
     *
     * @return LoopResult
     */
    public function parseResults(LoopResult $loopResult)
    {
        /** @var \PersoProduitPanier\Model\PersoProduitPanier $PersoProduitPanier */
        foreach ($loopResult->getResultDataCollection() as $PersoProduitPanier) {
            $loopResultRow = new LoopResultRow($PersoProduitPanier);

            $loopResultRow->set("personnalisation", $PersoProduitPanier->getComment());
            $loopResult->addRow($loopResultRow);
        }

        return $loopResult;
    }
}
