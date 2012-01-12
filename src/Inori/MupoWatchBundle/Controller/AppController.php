<?php

namespace Inori\MupoWatchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Config\FileLocator;

/**
 * @Route("/app")
 */
class AppController extends Controller
{
    protected $paramsLoc = false;

    /**
     * @Route("/sync/messages")
     */
    public function syncMessagesAction()
    {
        $em = $this->get('doctrine')->getEntityManager();
        $ta = $this->get('twitter_app');
        $ta->getApi()->decode_json_to_array = true;

//        $params = $this->getTwitterParameters();
//        $dms = $ta->getDirectMessages($params['last_recieved_msg']);
//
//        foreach ($dms as $message) {
//            $result = $this->parseMessage($message['text']);
//        }

//        $params['last_recieved_msg'] = $dms[0]['id_str'];
//        $this->saveTwitterParameters($params);
    }

    /**
     * @Route("/sync/friends")
     */
    public function syncFriendsAction()
    {
        $ta = $this->get('twitter_app');
        $ta->getApi()->decode_json_to_array = true;

        $followers = $ta->getApi()->get('statuses/followers');
        $friends = $ta->getApi()->get('statuses/friends');

        $diff = array_diff($followers, $friends);

        foreach ($diff as $follower) {
            $ta->follow($follower['screen_name']);
        }
    }

    protected function getTwitterParameters()
    {
        $fl = new FileLocator(array(__DIR__.'/../Resources/twitter'));
        $this->paramsLoc = $fl->locate('params.ini');

        $params = parse_ini_file($this->paramsLoc);

        return $params;
    }

    protected function saveTwitterParameters($parameters)
    {
        $string = "[twitter]";
        foreach ($parameters as $key => $param) {
            "\n".$string .= $key."=".$param;
        }

        file_put_contents($this->paramsLoc, $string);
    }

    protected function parseMessage($message)
    {
        $action = substr($message, 0, strpos($message, ' '));

        if ($action != 'add' || $action != 'check') {
            return 'Wrong message pattern, please try again!';
        }

        $action .= 'Message';

        return  $this->$method($message);
    }

    protected function addMessage($message)
    {
        preg_match('/add (?P<type>\w+) (?P<number>\d+) (?P<destination>\S+) (?P<station_before>\w+) (?P<time>\S+)/', $message, $matches);

        if (!empty($matches)) {
            $report = new \Inori\MupoWatchBundle\Entity\Report();
            $report->setData($matches);

            $this->get('doctrine')->getEntityManager()->persist($report);

            return 'Info added, thanks!';
        } else {
            return 'Wrong add pattern, please try again!';
        }
    }

    protected function checkMessage($message)
    {
        preg_match('/check (?P<type>\w+) (?P<number>\d+) (?P<destination>\w+)/', $message, $matches);

        if (!empty($matches)) {
            $reports = $this->get('doctrine')->getEntityManager()
                    ->getRepository('InoriMupoWatchBundle:Report')
                    ->getReports($matches);

            if (!empty($reports)) {
                $result = 'Latest 3 Mupos seen:';

                foreach ($reports as $report) {
                    $result .= ' '.$report->getType().' #'.$report->getNumber().' after '.$report->getStationBefore().';';
                }

                return $result;
            }

            return 'No Mupos found with your parameters';

        } else {
            return 'Wrong check pattern, please try again!';
        }
    }
}