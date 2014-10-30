<?php
/**
 * Created 25.09.14 15:04.
 *
 * PHP version 5
 *
 * @category AuthorityLabs
 * @package AuthorityLabs
 * @author Eugene Kuznetcov <easmith@mail.ru>
 */

class AuthorityLabs {

    /**
     * Base URL
     *
     * @var string
     */
    private $baseUrl = "http://api.authoritylabs.com";

    /**
     * Auth token
     *
     * @var string
     */
    private $auth_token = null;

    /**
     * Callback URL
     *
     * @var string
     */
    private $callback = null;

    /**
     * Supported search engines
     *
     * @var array
     */
    public $supportedEngines = ['google', 'yahoo', 'bing', 'yandex', 'baidu'];

    public function __construct($token, $callback = null)
    {
        $this->auth_token = $token;
        if (!is_null($callback)) $this->callback = $callback;
    }

    /**
     * Curl request
     *
     * @param string $url URL
     * @param string $data Post data
     *
     * @return mixed
     */
    private function request($url, $data = null)
    {
        $url = $this->baseUrl . $url;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Expect:"]);

        if ($data) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }

        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        curl_setopt($ch, CURLOPT_FAILONERROR, 0);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        return curl_exec($ch);
    }

    /**
     * Return account info
     *
     * @return array
     */
    public function accountInfo()
    {
        $url = "/account/{account_id}.json?";
        $url .= http_build_query(['auth_token' => $this->auth_token]);

        $result = $this->request($url);

        return json_decode($result, true);
    }

    /**
     * Return supported locations
     *
     * @param string $engine
     *
     * @return mixed
     */
    public function getSupported($engine = null)
    {
        if (!in_array($engine, $this->supportedEngines)) $engine = 'google';

        $result = $this->request("/supported/{$engine}.json");

        return json_decode($result, true);
    }

    /**
     * Adding to the Immediate Queue
     *
     * @param string $keyword Keyword
     * @param string $engine Search engine
     * @param string $locale Search locale
     * @param string $callback Callback URL
     *
     * @return mixed
     */
    public function immediateQueue($keyword, $engine = 'google', $locale = 'en-US', $callback = null)
    {
        $params = [
            'auth_token' => $this->auth_token,
            'keyword' => $keyword,
            'engine' => $engine,
            'locale' => $locale,
            'callback' => is_null($callback) ? $this->callback : $callback,
//            'pages_from' => '',
//            'geo' => ''
        ];

        if (!is_null($this->callback)) $params['callback'] = $this->callback;

        return $this->request("/keywords/priority", http_build_query($params));
    }

    /**
     * Adding to the Delayed Queue
     *
     * @param string $keyword Keyword
     * @param string $engine Search engine
     * @param string $locale Locale
     * @param string $callback Callback URL
     *
     * @return string
     */
    public function delayedQueue($keyword, $engine = 'google', $locale = 'en-US', $callback = null)
    {
        $params = [
            'auth_token' => $this->auth_token,
            'keyword' => $keyword,
            'engine' => $engine,
            'locale' => $locale,
            'callback' => is_null($callback) ? $this->callback : $callback,
//            'pages_from' => '',
//            'geo' => ''
        ];

        return $this->request("/keywords", http_build_query($params));
    }

    /**
     * Accessing Search Results Pages
     *
     * @param string $keyword Keyword
     * @param string $engine Search engine
     * @param string $locale Search locale
     * @param string $rankDate Rank date
     *
     * @return mixed
     */
    public function getResult($keyword, $engine = 'google', $locale = 'en-US', $rankDate = null)
    {
        $params = [
            'keyword' => $keyword,
            'data_format' => 'json',
            'auth_token' => $this->auth_token,
            'engine' => $engine,
            'locale' => $locale,
            'rank_date' => $rankDate ? $rankDate : date('Y-m-d'),
        ];

        $result = $this->request("/keywords/get.json?" . http_build_query($params));

        return json_decode($result, true);
    }
}