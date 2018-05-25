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

namespace PersoProduit\EventListeners;

use Thelia\Controller\Front\BaseFrontController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Thelia\Core\Event\Order\OrderEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Core\Event\Cart\CartEvent;
use PersoProduit\Model\PersoProduitPanier;
use PersoProduit\Model\PersoProduitPanierQuery;

use PersoProduit\Model\PersoProduitCommande;
use PersoProduit\Model\PersoProduitCommandeQuery;

use Thelia\Core\Event\Cart\CartItemDuplicationItem;
use Thelia\Model\OrderProductQuery;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Thelia\Core\Event\Coupon\CouponConsumeEvent;
use Thelia\Core\Event\Coupon\CouponCreateOrUpdateEvent;
use Thelia\Model\CartItemQuery;
use Thelia\Model\AttributeCombinationQuery;

use Thelia\Core\Event\TheliaFormEvent;

use Thelia\Model\AttributeAvI18nQuery;
/**
 * Class YoListener
 * @package YoT\EventListeners
 * @author Manuel Raynaud <manu@thelia.net>
 */
class PersoProduitListener implements EventSubscriberInterface
{
  protected $request;



  public function __construct(Request $request)
  {
      $this->request = $request;
  }

  public static function getSubscribedEvents()
  {
      return [
      TheliaEvents::FORM_BEFORE_BUILD . ".thelia_cart_add" => ['ajouterChampFormulaire', 255], //ajout d'un champs dans le formulaire d'ajout au panier
      TheliaEvents::CART_ADDITEM => [['saveComment', 10], ['addItem', 255]], //creer une nouvelle ligne a chaque ajout panier + traitement nouveau champs
      TheliaEvents::CART_ITEM_DUPLICATE => ['majVisuel', 255], //duplicate personnalisation on user login or other cart duplication

      TheliaEvents::ORDER_BEFORE_PAYMENT => ['majVisuelCmd', 25], //link personnalisation to cmd too
      ];
  }

//add couple cartItemId-OrderProductID in DB (to display it in invoice)
public function majVisuelCmd(OrderEvent $event)
{
  $orderProducts = OrderProductQuery::create()->findByOrderId($event->getOrder()->getId());
  foreach ($orderProducts as $orderProduct) {
        $comment = PersoProduitPanierQuery::create()->findOneByItemId($orderProduct->getCartItemId());
        if(is_null($comment) == false){
          $linkToOrder=new PersoProduitCommande();
          $linkToOrder->setOrderProdId($orderProduct->getId());
          $linkToOrder->setComment($comment->getComment());
          $linkToOrder->save();
        }

      }
}

//create new entry when a new cart is created (on user login)
public function majVisuel(CartItemDuplicationItem $event)
{
  if (null !== $visuel = PersoProduitPanierQuery::create()->findOneByItemId($event->getOldItem()->getId())) {
    $productComment = new PersoProduitPanier();
    $productComment->setItemId($event->getNewItem()->getId());
    $productComment->setComment($visuel->getComment());
    $productComment->save();//crete new entry
    $visuel->delete();//remove old entry
        }
}

//add new line in cart instead of increase quantity
public function addItem(CartEvent $event)
{
  $event->setAppend(false);
  $event->setNewness(true);
  $test=$this->request->get('item');
  if($test != null){
    $cartEvent = $event;
    $cartEvent->setCartItemId($test);
    $event->getDispatcher()->dispatch(TheliaEvents::CART_DELETEITEM, $cartEvent);
  }
}

//get file url and store it in DB
  public function saveComment(CartEvent $event)
  {
    $formData = $this->request->get("thelia_cart_add", []);
    $test=$formData['champs_perso'];
    $cart = $event->getCartItem();
    $cartId = $cart->getId();
        if ($cartId != null && $test != null) {
            $productComment = new PersoProduitPanier();
            $productComment->setItemId($cartId);
            $productComment->setComment($test);
            $productComment->save();
        }
  }

  public function ajouterChampFormulaire(TheliaFormEvent $event)
     {
         $event->getForm()->getFormBuilder()->add(
             'champs_perso',
             "text",
             [
                 'required' => true,
                 'label' => 'Personnalisation du produit',
                 'label_attr'  => [
                     'help' => 'Entrez le texte de Personnalisation'
                 ]
             ]
         );
     }

}
