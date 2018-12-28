<?
require_once __DIR__ . '/vendor/autoload.php';
	
use Dialogflow\WebhookClient;
use Dialogflow\Action\Responses\SimpleResponse;
Use Dialogflow\RichMessage\Payload;
use Dialogflow\Action\Responses\Image;



$agent = new WebhookClient(json_decode(file_get_contents('php://input'),true));

error_log(file_get_contents('php://input').PHP_EOL,3,'standartJson');

$mes = $agent->getRequestSource($agent);

if($mes  == 'viber'){
	
	$data = [
		"type"=> "picture",
		"text"=> "New Year picture",
		"media"=> "https://secure.gravatar.com/avatar/dc6a1427cdf0ac31bc084af8ef212c54?s=96&r=g"

	];	
	

}

if ($mes == 'telegram'){
	$menu = [
		"type"=> "text",
   		"text"=> "Pick a colo",
   		"reply_markup" => [
   			"inline_keyboard" => [
   				[
	   				"text"=> "Red",
	   				"callback_data"=> "Red",

   				],
   				[
	   				"text"=> "Red",
	   				"callback_data"=> "Red",

   				]
   			]
   		]
		
    ]; 
}

$data = $agent->getOriginalRequest();
$lang = $agent->getLocale();

$language['ru']['main_menu']['title'] = 'Привет из Telegram Вот Кнопки!';
$language['en']['main_menu']['title'] = 'Hello from Telegram Here!';

$data = $agent->getOriginalRequest();



$telegramButton = [
        'expectUserResponse' => false,
	    "text"=> $language[$lang]['main_menu']['title'],
	    "reply_markup"=> [
	      	"inline_keyboard"=> [
	        	[["text"=> "Red", "callback_data"=> "Red"]],
	        	[["text"=> "Red", "callback_data"=> "Red"],["text"=> "Red", "callback_data"=> "Red"],["text"=> "Red", "callback_data"=> "Red"]]
	    	]
	    ]

    ];
$viberButton = 	[
		"type"=> "picture",
		"text"=> "New Year picture",
		"media"=> "https://secure.gravatar.com/avatar/dc6a1427cdf0ac31bc084af8ef212c54?s=96&r=g"
	/*	"receiver"=>"",
		"min_api_version"=>1,
		"sender"=>["name"=>'dsdsad',"avatar"=>"http://avatar.example.com"],
		"tracking_data"=>"tracking data",
		"type"=>"text",
		"text"=>"Hello world!"*/
	];	  			
/*
$facebookButton = [
		"attachment"=> [
			"type"=> "audio",
				"payload"=> [
					"url"=> "https://sample-videos.com/audio/mp3/crowd-cheering.mp3"
				]
		]
];*/

//recipient
$facebookButton = [
	/*	"messaging_type"=> "RESPONSE",
		"recipient"=>[
		  "id"=>$data['payload']['data']['sender']['id']
		],
		"message"=>[
			"text"=>"hello, world!"
		]*/
];	
	
//$data = $agent->getOriginalRequest();
//$viberButton['receiver'] = $data['payload']['data']['userProfile']['id'];

//error_log(var_export($data['payload']['data']['userProfile']['id'],true).PHP_EOL,3,'agentid');

//$viberButton = $data;  


$button = '';

if($mes == 'telegram')
{
	$data = $agent->getOriginalRequest();
	$text = $data['payload']['data']['message']['text'];
	$id = $data['payload']['data']['message']['chat']['id'];
	$button = $telegramButton;
	
	$Viber = new Viber();
	$VM = $Viber->send_message(
		'wsu==',
		[
			'name' => 'Admin', // Имя отправителя. Максимум символов 28.
			'avatar' => 'http://avatar.example.com' // Ссылка на аватарку. Максимальный размер 100кб.
		],
		'Тестовое сообщение из telegram -'.$text,
		[  "keyboard"=>[
				"Type"=>"keyboard",
				"DefaultHeight"=>true,
				"Buttons"=>[
					[
					"Columns"=> 6,
					"Rows"=> 1,
					"TextHAlign"=> 'center',
					"ActionType"=>"reply",
					"ActionBody"=>"Ответить 201515",
					"Text"=>"Ответить 201515",
					"TextSize"=>"regular"
					],
					[
					"Columns"=> 6,
					"Rows"=> 1,
					"TextHAlign"=> 'center',
					"ActionType"=>"reply",
					"ActionBody"=>"Menu",
					"Text"=>"Главное меню",
					"TextSize"=>"regular"
					]
				]
			]
		]
	);
	
	error_log(var_export($VM,true).PHP_EOL,3,'VM');
	
	//wsu+U8zUw8ZkEu3cfePyCQ==
}
if($mes == 'viber')
{
	
	$data = $agent->getOriginalRequest();
	$text = $data['payload']['data']['message']['text'];
	$id = $data['payload']['data']['userProfile']['id'];
	
	$telegram = new Telegram('75252s');
	$keyboard = [
	    "reply_markup"=> [
	      	"inline_keyboard"=> [
	        	[["text"=> "Ответить пользователю", "callback_data"=> "Ответить 201515"]],
	        	[["text"=> "Главное меню", "callback_data"=> "Menu"]]
	    	]
	    ]	
	];
	
	
	$telegram->sendMessage(29,' Тестовое сообщение из viber -'.$text,$keyboard);
	$button = $viberButton;
}
if($mes == 'facebook') $button = $facebookButton;

$agent->reply(\Dialogflow\RichMessage\Payload::create($button));



//$suggestion = \Dialogflow\RichMessage\Suggestion::create(['1', '2', '2', '2', '2', '2', '2'],['2'],['1', '2'],['1', '2'],['1', '2']);
//$agent->reply($suggestion);


//$agent->reply(\Dialogflow\RichMessage\Text::create()->text('[
//          "[{"type":0,"platform":"viber","speech":"Это по умолчанию"},{"type":0,"platform":"telegram","speech":"А это телеграмм"},{"type":0,"platform":"viber","speech":"Ну а тут Вайбер"},{"type":1,"platform":"viber","title":"Карт","subtitle":"Субтитры","imageUrl":"https://developers.viber.com/images/devlogo.png","buttons":[{"text":"Название кнопки","postback":""}]},{"type":0,"speech":"Это по умолчанию"}]"
 //       ]'));
//$suggestion = \Dialogflow\RichMessage\Suggestion::create(['Suggestion one', 'Suggestion two']);
//$agent->reply($suggestion);
//$agent->reply(json_encode($data));

header('Content-type: application/json');

echo json_encode($agent->render());

error_log(var_export($agent,true).PHP_EOL,3,'agent');
error_log(json_encode($agent->render()).PHP_EOL,3,'agent-response');
error_log(var_export($mes,true).PHP_EOL,3,'agent-mes');
error_log(var_export($viberButton,true).PHP_EOL,3,'v');

//$agent = WebhookClient::fromData($_POST);

//error_log(var_export($agent,true).PHP_EOL,3,'agent');


class Telegram {

    protected $bot_token = "";
    protected $api_url = "";
    protected $channel_id = "";

    function __construct($bot_token, $channel_id = '') {

        $this->bot_token = $bot_token;
        $this->api_url = "https://api.telegram.org/bot" . $bot_token . "/";
        $this->channel_id = $channel_id;
    }

    /**
     * Делает запрос к серверу
     * 
     * @param resource $handle
     * 
     * @return boolean
     */
    protected function _exec_curl_request($handle) {
        $response = curl_exec($handle);
        if ($response === false) {
            $errno = curl_errno($handle);
            $error = curl_error($handle);
            error_log("Curl returned error $errno: $error\n");
            curl_close($handle);
            return false;
        }

        $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
        curl_close($handle);
        if ($http_code >= 500) {
            // do not wat to DDOS server if something goes wrong
            sleep(10);
            return false;
        } else if ($http_code != 200) {
            $response = json_decode($response, true);
            error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
            if ($http_code == 401) {
                throw new Exception('Invalid access token provided');
            }
            return false;
        } else {
            $response = json_decode($response, true);
            if (isset($response['description'])) {
                error_log("Request was successfull: {$response['description']}\n");
            }
            $response = $response['result'];
        }

        return $response;
    }

    /**
     * Подготовка запроса
     * 
     * @param string $method
     * @param array $parameters
     * 
     * @return boolean
     */
    protected function _apiRequest($method, $parameters) {
        if (!is_string($method)) {
            error_log("Method name must be a string\n");
            return false;
        }

        if (!$parameters) {
            $parameters = array();
        } else if (!is_array($parameters)) {
            error_log("Parameters must be an array\n");
            return false;
        }

        foreach ($parameters as $key => & $val) {
            // encoding to JSON array parameters, for example reply_markup
            if (!is_numeric($val) && !is_string($val)) {
                $val = json_encode($val);
            }
        }

        $url = $this->api_url . $method . '?' . http_build_query($parameters);

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);
        return $this->_exec_curl_request($handle);
    }

    /**
     * Отправка сообщения 
     * 
     * @param int $id_chat
     * @param string $sMessage
     * 
     * @return void
     */
    public function sendMessage($id_chat, $sMessage,$additionally = []) {

		$data['chat_id'] = $id_chat;
		$data['text'] = $sMessage;
		if(!empty($additionally)){
		   $data = array_merge($data,$additionally);
		}
	
        $this->_apiRequest('sendMessage', $data);
    }

}

class Viber
{
    private $url_api = "https://chatapi.viber.com/pa/";

    private $token = "48ecd085e8d6490f02";

    public function message_post
    (
        $from,          // ID администратора Public Account.
        array $sender,  // Данные отправителя.
        $text           // Текст.
    )
    {
        $data['from']   = $from;
        $data['sender'] = $sender;
        $data['type']   = 'text';
        $data['text']   = $text;
        return $this->call_api('post', $data);
    }
	
	public function send_message($to, array $sender, $text, $additionally = []){
		
				
		   $data["receiver"] = $to;
		   $data["sender"] = $sender;
		   $data["min_api_version"] = 1;
		   $data["tracking_data"] = "tracking data";
		   $data["type"] = "text";
		   $data["text"] = $text;
		   if(!empty($additionally)){
			   $data = array_merge($data,$additionally);
		   }
		
		
		return $this->call_api('send_message', $data);
	}
	
	/*
		stdClass::__set_state(array(
	   'status' => 0,
	   'status_message' => 'ok',
	   'message_token' => 5259346661333191765,
	   'chat_hostname' => 'SN-CHAT-36_',
	))
	*/

    private function call_api($method, $data)
    {
        $url = $this->url_api.$method;

        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\nX-Viber-Auth-Token: ".$this->token."\r\n",
                'method'  => 'POST',
                'content' => json_encode($data)
            )
        );
		
		error_log(var_export($options,true).PHP_EOL,3,'VM');
		
        $context  = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        return json_decode($response);
    }
}

?>

