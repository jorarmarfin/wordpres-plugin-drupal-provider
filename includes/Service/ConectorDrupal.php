<?php namespace DrupalProvider\Service;

use GuzzleHttp\Client;

class ConectorDrupal{
    protected $url;
    protected $headers;

    function __construct() {
        $options = get_option( 'drupal_provider_settings' );
        $this->url = $options['drupal_provider_text_domain'];
    }

    public function getServiceDrupal($type,$nid)
    {
        $content_type = $this->Call('GET','api/wordpress/'.$type.'-nid/'.$nid);
        
        return $content_type;
    }
    private function call($method,$endpoint,$data=null)
	{
		$guzzle_client = new Client();
		$options = [
			'headers' => $this->headers
		];
		if (isset($data)) {
			$options['json'] = $data;
		}
		$response = $guzzle_client->request($method, $this->url.$endpoint.'?_format=json', $options);
		$data = json_decode($response->getBody());
		return (array)$data[0];
	}
}