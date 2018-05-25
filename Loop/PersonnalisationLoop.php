<?php

namespace PersoProduit\Loop;

use PersoProduit\Model\PersoProduitPanierQuery;
use Thelia\Core\Template\Element\BaseLoop;
use Thelia\Core\Template\Element\LoopResult;
use Thelia\Core\Template\Element\LoopResultRow;
use Thelia\Core\Template\Element\PropelSearchLoopInterface;
use Thelia\Core\Template\Loop\Argument\Argument;
use Thelia\Core\Template\Loop\Argument\ArgumentCollection;

class PersonnalisationLoop extends BaseLoop implements PropelSearchLoopInterface
{
    /**
     * @return \Thelia\Core\Template\Loop\Argument\ArgumentCollection
     */
    protected function getArgDefinitions()
    {
        return new ArgumentCollection(
            Argument::createIntTypeArgument('item_id', null, true)
        );
    }

    /**
     * this method returns a Propel ModelCriteria
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function buildModelCriteria()
    {
        $PersoProduitPanierQuery = PersoProduitPanierQuery::create()->filterByItemId($this->getItemId());

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
