<?php


namespace Vibalco\MainBundle\Service;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Vibalco\MainBundle\Entity\CurrencyExchange;

class ConvExchangeService implements IExchangeService
{
    protected $settings;
    protected $em;

    /**
     * CurrencyExchangeService constructor.
     * @param array $settings
     * @param EntityManager $em
     */
    public function __construct($settings, $em)
    {
        $this->settings = $settings;
        $this->em = $em;
    }

    public function getLatestCurrency()
    {
        $result = null;
        try {
            $entity = $this->em->getRepository('MainBundle:CurrencyExchange')
                ->getLastCurrency();

            if (!empty($entity)) {
                $result = $entity;
            } else {
                $apikey = $this->settings['key'];
                $url = $this->settings['api'];
                $base = $this->settings['base'];
                $symbols = $this->settings['symbols'];
                $symbols = explode(',', $symbols);
                $queryParts = array_map(function ($symbol) use ($base) {
                    return "{$base}_{$symbol}";
                }, $symbols);
                $query = implode(',', $queryParts);
                $url = $url . '?q=' . $query . '&compact=ultra&apiKey=' . $apikey;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $json = curl_exec($ch);
                $eer = curl_error($ch);
                if ($eer) {
                    throw new \Exception($eer);
                }
                curl_close($ch);
                if (!empty($json)) {
                    $rates = str_replace($base . '_', '', $json);
                    $date = new \DateTime();
                    $currencyExchange = new CurrencyExchange();
                    $currencyExchange->setBase($base);
                    $currencyExchange->setRates($rates);
                    $currencyExchange->setRetrievedAt($date);
                    $this->em->persist($currencyExchange);
                    $this->em->flush();
                    $result = $currencyExchange;
                }
            }
        } catch (NonUniqueResultException $e) {
            $result = null;
        } catch (\Exception $exception) {
            $result = null;
        }

        return $result;
    }

    public function getDebugCurrency()
    {
        return null;
    }

    public function convertCurrency($amount, $from, $to)
    {
        $apikey = $this->settings['key'];
        $url = $this->settings['api'];
        $from_Currency = urlencode($from);
        $to_Currency = urlencode($to);
        $query = "{$from_Currency}_{$to_Currency}";

        // change to the free URL if you're using the free version
        $json = file_get_contents("{$url}?q={$query}&compact=ultra&apiKey={$apikey}");
        $obj = json_decode($json, true);

        $val = floatval($obj["$query"]);


        $total = $val * $amount;
        return number_format($total, 2, '.', '');
    }
}