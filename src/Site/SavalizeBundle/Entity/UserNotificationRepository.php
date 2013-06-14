<?php

namespace Site\SavalizeBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * UserNotificationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserNotificationRepository extends EntityRepository
{
	public function count($id) {
        $query = $this->getEntityManager()->createQuery('
            SELECT count(n) as notfCount
            FROM SiteSavalizeBundle:UserNotificationSeen n
            where n.customer = :id
            ');
        $query->setParameter('id', $id);
        return $query->getResult();
    }

    public function showNotifications($id , $page, $maxResults) {
        if ($page < 1) {
            return array();
        }
        $page--;   // to start from zero

        $query = $this->getEntityManager()->createQuery('
        SELECT u.id, u.title, u.content , u.releasedAt , N.seen
        FROM SiteSavalizeBundle:UserNotificationSeen N
        JOIN SiteSavalizeBundle:UserNotification u
        where N.customer = :id
        and u.id = N.userNotification
        order by u.releasedAt desc
        ');

        if ($maxResults) {
            $query->setFirstResult($page * $maxResults);
            $query->setMaxResults($maxResults);
        }
        $query->setParameter('id', $id);
        return $query->getResult();
    }

        public function updateSeen($notf_id) {
        $query = $this->getEntityManager()->createQuery('
            UPDATE SiteSavalizeBundle:UserNotificationSeen n
            SET n.seen = 1
            where n.userNotification = :notf_id
            ');
        $query->setParameter('notf_id', $notf_id);
        $result = $query->execute();
        return $result;
    }
}
