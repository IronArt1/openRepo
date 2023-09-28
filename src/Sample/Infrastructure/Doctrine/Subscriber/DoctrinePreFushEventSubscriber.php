<?php

namespace App\Sample\Infrastructure\Doctrine\Subscriber;

use Doctrine\ORM\Events;
use App\PayPal\IPN\IpnEntity;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;

/**
 * PreFlush event subscriber is run right after
 * "flush()" call but before saving data into DB
 *
 * class DoctrinePreFlushEventSubscriber
 */
class DoctrinePreFlushEventSubscriber implements EventSubscriber
{
    /**
     * preFlush is called inside EntityManager::flush() before anything else.
     *
     * @param PreFlushEventArgs $args
     * @return void
     */
    public function preFlush(PreFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        $checkAndEncodeIpnEntityData = function($entity) {
            if ($entity instanceof IpnEntity) {
                $data = $entity->getData();

                if (!isset($data['transition'])) {
                    $addHaystack = function($ord, $key) use (&$data) {
                        $data[$key] .= chr($ord);
                    };
                    $setBin = function($ord, $string, $i, $n) {
                        for ($j = 1; $j <= $n; $j++) {
                            $ord |= (ord($string[$i + $j]) & 0x3F << 6 * ($n - $j));
                        }

                        return $ord;
                    };
                    foreach (['first_name', 'last_name', 'payer_business_name'] as $key) {
                        if (isset($data[$key])) {
                            $currentString = '';
                            for ($i = 0; $i < strlen($data[$key]); $i++) {
                                $encoding = mb_detect_encoding($data[$key][$i], mb_detect_order(), true);
                                if (!isset($encoding) || $encoding != 'ASCII') {
                                    $currentString .= mb_convert_encoding($data[$key][$i], $encoding, 'ASCII');
                                } else {
                                    $currentString .= $data[$key][$i];
                                }
                            }
                            $data[$key] = '';
                            for ($i = 0; $i < strlen($currentString); $i++) {
                                $currentOrd = ord($currentString[$i]);
                                if ($currentOrd >= 127) {
                                    if ($currentOrd >= 192 && $currentOrd <= 223) {
                                        switch (ord($currentString[$i + 1])) {
                                            case 161:
                                                $currentOrd = ($currentOrd & 0x3) << 5 | (ord($currentString[$i + 1]) & 0xF0) >> 7;
                                                break;
                                            case 169:
                                                $currentOrd = ($currentOrd & 0xC0) >> 1 | (ord($currentString[$i + 1]) & 0xF0) >> 5;
                                                break;
                                            case 171:
                                                $currentOrd = ($currentOrd & 0xF0) >> 1 | (ord($currentString[$i + 1]) & 0x3A) >> 3;
                                                break;
                                            case 188:
                                                $currentOrd = (($currentOrd & 0x0F) << 5 | ($currentOrd & 0xF0) >> 2) | (ord($currentString[$i + 1]) & 0xA0) >> 5;
                                                break;
                                            default:
                                                $currentOrd = $setBin(($currentOrd & 0x1F) <<  6, $currentString, $i, 1);
                                        }
                                        $i++;
                                    } elseif ($currentOrd >= 224 && $currentOrd <= 239) {
                                        $currentOrd = $setBin(($currentOrd & 0x0F) <<  12, $currentString, $i, 2);
                                        $i += 2;
                                    } elseif ($currentOrd >= 240 && $currentOrd <= 247) {
                                        if ($currentOrd == 246) {
                                            $currentOrd = ($currentOrd & 0xF0) >> 4 | ord($currentString[$i + 1]);
                                        } else {
                                            $currentOrd = $setBin(($currentOrd & 0x0F) <<  18, $currentString, $i, 3);
                                            $i += 3;
                                        }
                                    } elseif ($currentOrd == 248) {
                                        $currentOrd = ($currentOrd & 0xF0) >> 4 | (ord($currentString[$i + 1]) & 0xB0) << 1;
                                    } elseif ($currentOrd >= 250) {
                                        $currentOrd = ($currentOrd & 0x1F) << 2 | (ord($currentString[$i + 1]) & 0xBB) >> 3;
                                    }
                                }
                                $addHaystack($currentOrd, $key);
                            }
                        }
                    }

                    $data['transition'] = 1;
                    $entity->setData($data);
                }

                array_walk($data, function(&$value, $key) {
                    $value = "{$key}: {$value}; ";
                });
            }
        };

        if ($em->getConnection()->getTransactionNestingLevel()) {
            foreach ($uow->getIdentityMap() as $im) {
                foreach ($im as $entity) {
                    $checkAndEncodeIpnEntityData($entity);
                }
            }
        } else {
            $entities = array_merge(
                $uow->getScheduledEntityInsertions(),
                $uow->getScheduledEntityUpdates()
            );

            foreach ($entities as $entity) {
                $checkAndEncodeIpnEntityData($entity);
            }
        }
    }

    /**
     * A subscribed event is: preFlush
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [
            Events::preFlush,
        ];
    }
}
