<?php
namespace Vibalco\MainBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NonUniqueResultException;
use Vibalco\MainBundle\Entity\CurrencyExchange;

class CurrencyExchangeService implements IExchangeService
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

    /**
     * Return last success retrieval for currency exchange
     * @return mixed|CurrencyExchange|null
     */
    public function getLatestCurrency()
    {
        $result = null;
        try {
            $entity = $this->em->getRepository('MainBundle:CurrencyExchange')
                ->getLastCurrency();
        } catch (NonUniqueResultException $e) {
            $result = null;
        }

        if (!empty($entity)) {
            $result = $entity;
        } else {
            try {
                $url = $this->settings['api'] . '?apikey=' . $this->settings['key'];
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $output = curl_exec($ch);
                curl_close($ch);
                $data = json_decode($output, true);
                if ($data) {
                    $ce = new CurrencyExchange();
                    $retrievedAt = new \DateTime($data['date']);
                    $ce->setRetrievedAt($retrievedAt);
                    $ce->setBase($data['base']);
                    $ce->setRates(json_encode($data['rates']));
                    $this->em->persist($ce);
                    $this->em->flush();
                    $result = $ce;
                }
            } catch (\Exception $exception) {
                return null;
            }
        }
        return $result;
    }

    /**
     * @return CurrencyExchange
     * @throws \Exception
     */
    public function getDebugCurrency() {
        $ce = new CurrencyExchange();
        $ce->setRetrievedAt(new \DateTime('2020-08-23 00:01:00+00'));
        $ce->setBase('USD');
        $ce->setRates('{"FJD":"2.13265","MXN":"21.9713","STD":"20973.366047","LVL":"0.656261","SCR":"17.83","CDF":"1949.06","BBD":"2.0","GTQ":"7.7","CLP":"793.13","HNL":"24.6579","UGX":"3670.0","ZAR":"17.1574","TND":"2.73635","CUC":"1.0","BSD":"1.0","SLL":"9987.77","SDG":"55.2914","IQD":"1193.81","CUP":"26.5","GMD":"52.4","TWD":"29.398","RSD":"99.675","DOP":"58.49","KMF":"420.1","BCH":"0.003480742790511495","MYR":"4.18","FKP":"0.764321","XOF":"556.058","GEL":"3.0883","BTC":"0.00008556497483320177","UYU":"42.875","MAD":"9.184","CVE":"94.34","TOP":"2.2853","AZN":"1.69903","OMR":"0.384995","PGK":"3.53045","KES":"108.0","SEK":"8.79616","BTN":"74.9245","UAH":"27.46","GNF":"9655.0","ERN":"15.075","MZN":"71.4683","SVC":"8.7548","ARS":"73.575","QAR":"3.641","IRR":"21000.0","MRO":"357.0","CNY":"6.91945","THB":"31.55","UZS":"10232.5","XPF":"101.158","MRU":"38.1762","BDT":"84.81","LYD":"1.364","BMD":"1.0","KWD":"0.30591","PHP":"48.64","RUB":"74.796","PYG":"6962.78","ISK":"137.835","JMD":"149.89","COP":"3836.0","MKD":"52.275","USD":"1.0","DZD":"128.356","PAB":"1.0","GGP":"0.763958","SGD":"1.3717","ETB":"35.989","JEP":"0.763958","ETC":"0.14719952896150731","KGS":"77.7374","SOS":"578.5","VEF":"9.9875","VUV":"113.175248","LAK":"9128.34","ETH":"0.002524519394620249","BND":"1.3717","ZEC":"0.012989543417548873","ZMK":"5253.075255","XAF":"561.13","LRD":"199.515","XAG":"0.03734831","CHF":"0.9116","HRK":"6.3883","ALL":"104.6","DJF":"178.025","VES":"309927.0","ZMW":"19.014","TZS":"2320.0","VND":"23176.0","XAU":"0.00051541","DASH":"0.010994508243132556","AUD":"1.39655","ILS":"3.4026","GHS":"5.77793","GYD":"209.215","KPW":"900.068","BOB":"6.905","KHR":"4091.85","MDL":"16.496","IDR":"14770.7","KYD":"0.820001","XRP":"3.493449781659389","AMD":"485.045","BWP":"11.6279","SHP":"0.762893","TRY":"7.3386","LBP":"1512.0","TJS":"10.3175","JOD":"0.709","AED":"3.67295","HKD":"7.75035","RWF":"966.212","EUR":"0.8477","LSL":"17.1571","DKK":"6.31125","CAD":"1.31775","BGN":"1.65795","MMK":"1362.7","MUR":"39.7","NOK":"9.01173","SYP":"1257.5","IMP":"0.763958","GIP":"0.764321","RON":"4.1041","LKR":"184.6","NGN":"382.0","CRC":"594.055","CZK":"22.1148","PKR":"168.35","XCD":"2.71","ANG":"1.78982","HTG":"111.793","LTC":"0.016582372937567365","BHD":"0.377005","KZT":"419.88","SRD":"7.475","SZL":"17.1583","LTL":"3.224845","SAR":"3.7506","TTD":"6.77725","YER":"249.435","MVR":"15.46","AFN":"77.1407","INR":"74.9215","AWG":"1.79","KRW":"1191.99","NPR":"119.75","JPY":"105.805","MNT":"2849.58","AOA":"583.479","PLN":"3.74535","GBP":"0.763971","SBD":"8.20343","BYN":"2.54628","HUF":"297.602","XLM":"9.75362347111952","BYR":"24843.27","BIF":"1929.05","MWK":"746.37","MGA":"3850.0","XDR":"0.708364","EOS":"0.2971768202080238","BZD":"2.0157","BAM":"1.65797","EGP":"15.93","MOP":"7.989","NAD":"17.222","NIO":"34.855","PEN":"3.58405","NZD":"1.52858","WST":"2.62295","TMT":"3.51","BRL":"5.6197"}');
        return $ce;
    }
}