<?php

class PayOrderListener
{
    public static function onSuccessPay(PayOrderEvent $event)
    {
        $order = $event->getOrder();

        $module = Yii::app()->getModule('order');

        $from  = $module->notifyEmailFrom ? : Yii::app()->getModule('yupe')->email;

        //администратору
        $to = $module->getNotifyTo();

        $body = Yii::app()->getControler()->renderPartial('/email/newOrderAdmin', ['order' => $order], true);

        foreach ($to as $email) {
            $email = trim($email);
            if ($email) {
                Yii::app()->mail->send(
                    $from,
                    $email,
                    Yii::t('OrderModule.order', 'Новый заказ №{n} в магазине {site}', array('{n}' => $order->id, '{site}' => Yii::app()->getModule('yupe')->siteName)),
                    $body
                );
                Yii::app()->mail->reset();
            }
        }

        //пользователю
    }
} 